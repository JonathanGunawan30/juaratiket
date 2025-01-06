<?php

namespace App\Filament\Resources\BookingTransactionResource\Widgets;

use App\Models\BookingTransaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BookingTransactionStats extends BaseWidget
{
    protected function getStats(): array
    {
        $totalTransactions = BookingTransaction::count();
        $approvedTransaction = BookingTransaction::where('is_paid', true)->count();
        $totalRevenue = BookingTransaction::where('is_paid', true)->sum('total_amount');

        return [
            Stat::make('Total Transactions', $totalTransactions)
                ->description('All transactions')
                ->descriptionIcon('heroicon-o-document-text'),

            Stat::make('Approved Transactions', $approvedTransaction)
                ->description('Approved transactions')
                ->descriptionIcon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Total Revenue', 'IDR ' . number_format($totalRevenue))
                ->description('Revenue from approved transactions')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success'),
        ];
    }
}
