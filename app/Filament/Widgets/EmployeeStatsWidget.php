<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\Department;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Karyawan', Employee::count())
                ->icon('heroicon-o-users'),

            Stat::make('Karyawan Aktif', Employee::where('status', 'aktif')->count())
                ->color('success')
                ->icon('heroicon-o-check-circle'),

            Stat::make('Jumlah Departemen', Department::count())
                ->icon('heroicon-o-building-office'),
        ];
    }
}
