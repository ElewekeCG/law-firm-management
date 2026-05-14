<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('calendar:sync')->hourly();
        $schedule->command('slots:generate')
            ->dailyAt('00:00')
            ->weekdays();

        // generate 30 days ahead every monday
        $schedule->command('slots:generate', [
            now()->toDateString(),
            now()->addDays(30)->toDateString()
        ])->weeklyOn(1, '00:00');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
