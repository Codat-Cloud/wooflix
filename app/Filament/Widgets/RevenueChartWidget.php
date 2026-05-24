<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class RevenueChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;
    
    protected ?string $heading =
        'Revenue (Last 7 Days)';

    protected function getData(): array
    {
        $days = collect(range(6, 0))
            ->map(fn ($day) =>
                now()->subDays($day)
            );

        $revenue = $days->map(function ($day) {

            return Order::whereDate(
                'created_at',
                $day
            )->sum('total_amount');

        });

        return [

            'datasets' => [

                [

                    'label' => 'Revenue',

                    'data' => $revenue,

                ],

            ],

            'labels' => $days
                ->map(fn ($day) =>
                    $day->format('D')
                )
                ->toArray(),

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}