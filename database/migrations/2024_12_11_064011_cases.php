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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('suitNumber')->unique();
            $table->foreignId('clientId')->constrained('users');
            $table->foreignId('lawyerId')->constrained('users');
            $table->string('title');
            $table->string('type');
            $table->string('status');
            $table->date('startDate');
            $table->string('assignedCourt');
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
        Schema::dropIfExists('cases');
    }
};
