<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RevenueStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {

        $todayRevenue = Order::whereDate(
            'created_at',
            today()
        )->sum('total_amount');

        $totalOrders = Order::count();

        $pendingOrders = Order::where(
            'status',
            'pending'
        )->count();

        $lowStock = ProductVariant::where(
            'stock',
            '<=',
            5
        )->count();

        return [

            Stat::make(
                'Today Revenue',
                '₹' . number_format($todayRevenue)
            )
                ->description('Today sales')
                ->color('success'),

            Stat::make(
                'Total Orders',
                number_format($totalOrders)
            )
                ->description('All time orders')
                ->color('primary'),

            Stat::make(
                'Pending Orders',
                $pendingOrders
            )
                ->description('Needs attention')
                ->color('warning'),

            Stat::make(
                'Low Stock Products',
                $lowStock
            )
                ->description('Stock <= 5')
                ->color('danger'),

        ];
    }
}