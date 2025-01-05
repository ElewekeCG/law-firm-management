<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PropertiesSeeder::class);
        $this->call(TenantsSeeder::class);
        $this->call(TransactionsSeeder::class);
        $this->call(CasesSeeder::class);
        $this->call(ProceedingsSeeder::class);
        $this->call(AppointmentsSeeder::class);
        $this->call(NotificationsSeeder::class);
    }
}
