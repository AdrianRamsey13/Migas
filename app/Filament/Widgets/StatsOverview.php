<?php

namespace App\Filament\Widgets;

use App\Models\Asset;
use App\Models\WorkOrder;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Assets', Asset::count())
                ->description('Semua aset terdaftar')
                ->icon('heroicon-o-cog-6-tooth')
                ->color('info'),

            Stat::make('Assets Aktif', Asset::where('status', 'active')->count())
                ->description('Beroperasi normal')
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('Assets Maintenance', Asset::where('status', 'maintenance')->count())
                ->description('Sedang dalam perawatan')
                ->icon('heroicon-o-wrench')
                ->color('warning'),

            Stat::make('Open Work Orders', WorkOrder::whereNotIn('status', ['completed', 'closed', 'rejected'])->count())
                ->description('WO yang masih berjalan')
                ->icon('heroicon-o-clipboard-document-list')
                ->color('primary'),

            Stat::make('WO Critical', WorkOrder::where('priority', 'critical')
                ->whereNotIn('status', ['completed', 'closed', 'rejected'])
                ->count())
                ->description('Perlu perhatian segera')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),

            Stat::make('WO Completed', WorkOrder::where('status', 'completed')->count())
                ->description('Selesai dikerjakan')
                ->icon('heroicon-o-check-badge')
                ->color('success'),
        ];
    }
}
