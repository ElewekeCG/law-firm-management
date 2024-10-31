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
            $table->string('title');
            $table->string('type');
            $table->string('status');
            $table->unsignedBigInteger('clientId');
            $table->foreign('clientId')
                  ->references('id')
                  ->on('clients')
                  ->onDelete('cascade');
            $table->string('suitNumber')->unique();
            $table->date('startDate');
            $table->dateTime('nextAdjournedDate');
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
