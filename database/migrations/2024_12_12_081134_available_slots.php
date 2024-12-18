<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('available_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyerId')->constrained('users');
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->enum('status', ['available', 'booked'])->default('available');
            $table->timestamps();
            // to avoid one lawyer getting booked twice for the same slot
            $table->unique(['lawyerId', 'startTime', 'endTime']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
