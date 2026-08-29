<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Models\Employee;
use Spatie\Activitylog\Models\Activity;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('riwayat')
                ->label('Riwayat Perubahan')
                ->icon('heroicon-o-clock')
                ->modalHeading('Riwayat Perubahan Data Karyawan')
                ->modalWidth('4xl')
                ->modalContent(function () {
                    $activities = Activity::where('subject_type', Employee::class)
                        ->latest()
                        ->get();

                    return view('filament.employee-activity-log', ['activities' => $activities]);
                })
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Tutup'),

            Actions\CreateAction::make(),
        ];
    }
}
