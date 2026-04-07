<?php

namespace App\Console;
use App\Models\Available_slots;
use App\Models\User;
use Carbon\Carbon;

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
        $schedule->command('slots:generate')->dailyAt('08:16');
        // $schedule->call(function(){
        //     $date = $this->argument('date')
        //     ? Carbon::parse($this->argument('date'))->startOfDay()
        //     : now()->startOfDay();

        // $endDate = $this->argument('end_date')
        //     ? Carbon::parse($this->argument('end_date'))->endOfDay()
        //     : $date->copy()->endOfDay();
        // $this->info("Generating slots from: {$date->toDateString()} to {$endDate->toDateString()}");

        // $lawyers = User::lawyers()->get();

        // while ($date <= $endDate) {
        //     $this->info("processing slots for date: {$date->toDateString()}");

        //     foreach ($lawyers as $lawyer) {
        //         $slots = Available_slots::generateSlots($lawyer->id, $date);
        //         $this->info("Generated {$slots->count()} slots for {$lawyer->name}");
        //     }

        //     // move to next day
        //     $date->addDay();
        // }

        // });
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
