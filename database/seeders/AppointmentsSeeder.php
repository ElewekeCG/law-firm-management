<?php

namespace Database\Seeders;
use App\Models\Appointments;
use Illuminate\Database\Seeder;

class AppointmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appointments::factory()->count(50)->create();
    }
}
