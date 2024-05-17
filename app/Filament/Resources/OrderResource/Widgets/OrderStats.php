<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Order Baru', Order::query()
                ->where('status', '=', 'new')
                ->count()),
            Stat::make('Order di Proses', Order::query()
                ->where('status', '=', 'processing')
                ->count()),
            Stat::make('Order Terkirim', Order::query()
                ->where('status', '=', 'delivered')
                ->count()),
            Stat::make(
                'Order Rata Rata',
                Number::currency(
                    Order::query()
                        ->avg('grand_total'),
                    in: 'IDR',
                    locale: 'id'
                )
            ),
        ];
    }
}
