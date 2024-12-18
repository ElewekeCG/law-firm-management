<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Available_slots;
use Carbon\Carbon;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class GenerateSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'slots:generate
    {date?}
    {end_date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate available slots for all lawyers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = $this->argument('date')
            ? Carbon::parse($this->argument('date'))->startOfDay()
            : now()->startOfDay();

        $endDate = $this->argument('end_date')
            ? Carbon::parse($this->argument('end_date'))->endOfDay()
            : $date->copy()->endOfDay();
        $this->info("Generating slots from: {$date->toDateString()} to {$endDate->toDateString()}");

        $lawyers = User::lawyers()->get();

        while ($date <= $endDate) {
            $this->info("processing slots for date: {$date->toDateString()}");

            foreach ($lawyers as $lawyer) {
                $slots = Available_slots::generateSlots($lawyer->id, $date);
                $this->info("Generated {$slots->count()} slots for {$lawyer->name}");
            }

            // move to next day
            $date->addDay();
        }

        return SymfonyCommand::SUCCESS;
    }
}
