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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->date('paymentDate');
            $table->string('entityType');
            $table->unsignedBigInteger('entityId');
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->enum('subType', ['legalFee', 'rent'],)->nullable(); //only for credit transactions
            $table->text('narration');
            $table->unsignedBigInteger('propertyId')->nullable();
            $table->timestamps();
            $table->foreign('propertyId')->references('id')->on('properties')->onDelete('cascade');

            // add index for polymorphic fields
            $table->index(['entityId', 'entityType']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
