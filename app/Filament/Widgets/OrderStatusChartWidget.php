<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrderStatusChartWidget extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading =
        'Order Status Breakdown';

    protected function getData(): array
    {
        return [

            'datasets' => [

                [

                    'data' => [

                        Order::where('status', 'pending')->count(),

                        Order::where('status', 'processing')->count(),

                        Order::where('status', 'shipped')->count(),

                        Order::where('status', 'delivered')->count(),

                        Order::where('status', 'cancelled')->count(),

                    ],

                ],

            ],

            'labels' => [

                'Pending',
                'Processing',
                'Shipped',
                'Delivered',
                'Cancelled',

            ],

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}