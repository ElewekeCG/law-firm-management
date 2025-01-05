<?php

namespace Database\Seeders;

use App\Models\transactions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        transactions::factory()->count(50)->create();
    }
}
