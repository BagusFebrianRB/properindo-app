<?php

namespace App\Filament\Widgets;

use App\Models\Task;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TaskStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $overdue = Task::where('status', '!=', 'selesai')
            ->whereDate('deadline', '<', now())
            ->count();

        $dueSoon = Task::where('status', '!=', 'selesai')
            ->whereDate('deadline', '>=', now())
            ->whereDate('deadline', '<=', now()->addDays(3))
            ->count();

        return [
            Stat::make('Total Pekerjaan', Task::count())
                ->icon('heroicon-o-clipboard-document-list'),

            Stat::make('Sedang Proses', Task::where('status', 'proses')->count())
                ->color('warning')
                ->icon('heroicon-o-arrow-path'),

            Stat::make('Terlambat', $overdue)
                ->color($overdue > 0 ? 'danger' : 'success')
                ->icon('heroicon-o-exclamation-triangle'),

            Stat::make('Mendekati Deadline (≤3 hari)', $dueSoon)
                ->color($dueSoon > 0 ? 'warning' : 'success')
                ->icon('heroicon-o-clock'),
        ];
    }
}
