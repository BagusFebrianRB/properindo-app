<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Filament\Notifications\Notification;

class CheckTaskDeadlines extends Command
{
    protected $signature = 'tasks:check-deadlines';

    protected $description = 'Cek pekerjaan yang mendekati atau melewati deadline, lalu kirim notifikasi';
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::where('status', '!=', 'selesai')
            ->whereDate('deadline', '<=', now()->addDays(3))
            ->with('pic')
            ->get();

        if ($tasks->isEmpty()) {
            $this->info('Tidak ada pekerjaan yang mendekati/melewati deadline.');
            return;
        }

        $admins = User::all();

        foreach ($tasks as $task) {
            $isOverdue = $task->deadline->isPast();
            $label = $isOverdue ? 'TERLAMBAT' : 'MENDEKATI DEADLINE';

            foreach ($admins as $admin) {
                Notification::make()
                    ->title("{$label}: {$task->task_name}")
                    ->body("PIC: {$task->pic->name} — Deadline: {$task->deadline->format('d/m/Y')}")
                    ->color($isOverdue ? 'danger' : 'warning')
                    ->icon($isOverdue ? 'heroicon-o-exclamation-triangle' : 'heroicon-o-clock')
                    ->sendToDatabase($admin);
            }

            $this->line("Notifikasi terkirim: {$task->task_name} ({$label})");
        }
    }
}
