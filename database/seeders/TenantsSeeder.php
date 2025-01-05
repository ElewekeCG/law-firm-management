<?php

namespace Database\Seeders;

use App\Models\tenants;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        tenants::factory()->count(50)->create();
    }
}
