<?php

namespace App\Console;

use App\Jobs\ConcluirAgendamentos;
use Illuminate\Console\Scheduling\Schedule;
use App\Jobs\VerificarPagamento;
use App\Jobs\NotificationJob;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule):void
    {
        $schedule->job(new NotificationJob())->everyMinute();
        $schedule->job(new ConcluirAgendamentos())->everyMinute();

    }
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
