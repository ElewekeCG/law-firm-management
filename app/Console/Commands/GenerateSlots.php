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
    {date? : Start date (defaults to today)}
    {end_date? : End date (defaults to start date)}';

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

        // Validate date range
        if ($date->gt($endDate)) {
            $this->error('Start date cannot be after end date.');
            return SymfonyCommand::FAILURE;
        }

        $this->info("Generating slots from: {$date->toDateString()} to {$endDate->toDateString()}");

        $lawyers = User::lawyers()->get();

        if ($lawyers->isEmpty()) {
            $this->warn('No lawyers found. Exiting.');
            return SymfonyCommand::SUCCESS;
        }

        $current = $date->copy();

        while ($current->lte($endDate)) {
            // skip weekends
            if ($current->isWeekend()) {
                $this->line("Skipping weekend: {$current->toDateString()}");
                $current->addDay();
                continue;
            }

            $this->info("processing slots for date: {$date->toDateString()}");

            foreach ($lawyers as $lawyer) {
                $slots = Available_slots::generateSlots($lawyer->id, $current->copy());

                if ($slots->isEmpty()) {
                    $this->warn("  No slots generated for {$lawyer->name} (slots may already exist)");
                } else {
                    $this->info("  Generated {$slots->count()} slots for {$lawyer->name}");
                }

            }
            $current->addDay();
        }
        $this->info('slot generation complete');
        return SymfonyCommand::SUCCESS;
    }
}
