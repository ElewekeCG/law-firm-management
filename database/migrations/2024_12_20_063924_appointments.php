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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lawyerId')->constrained('users');
            $table->foreignId('clientId')->constrained('users');
            $table->foreignId('caseId')->nullable()->constrained('cases');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('startTime');
            $table->dateTime('endTime');
            $table->enum('type', ['consultation', 'case_meeting', 'court_appearance']);
            $table->enum('status', ['scheduled', 'confirmed', 'completed', 'cancelled'])->default('scheduled');
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
