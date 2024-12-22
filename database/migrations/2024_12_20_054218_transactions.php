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
            $table->enum('type', ['credit', 'debit'])->default('credit');
            $table->enum('subType', ['legalFee', 'rent'],)->nullable(); //only for credit transactions
            $table->unsignedBigInteger('tenantId')->nullable();
            $table->foreignId('clientId')->nullable()->constrained('users');
            $table->unsignedBigInteger('propertyId')->nullable();
            $table->text('narration');
            $table->timestamps();

            $table->foreign('tenantId')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('propertyId')->references('id')->on('properties')->onDelete('cascade');

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
