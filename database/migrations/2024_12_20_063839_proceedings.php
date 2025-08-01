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
        Schema::create('proceedings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caseId');
            $table->foreign('caseId')
                  ->references('id')
                  ->on('cases')
                  ->onDelete('cascade');
            $table->text('description');
            $table->string('requiredDoc')->nullable();
            $table->date('dueDate')->nullable();
            $table->enum('docStatus', ['pending', 'done'])->default('pending');
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
        Schema::dropIfExists('proceedings');
    }
};
