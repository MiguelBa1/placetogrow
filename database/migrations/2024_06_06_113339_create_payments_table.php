<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('payment_reference');
            $table->string('request_id');
            $table->string('process_url');
            $table->dateTime('expires_in');
            $table->string('internal_reference');
            $table->string('franchise');
            $table->string('payment_method');
            $table->string('payment_method_name');
            $table->string('issuer_name');
            $table->string('receipt');
            $table->string('authorization');
            $table->string('status');
            $table->string('status_message');
            $table->dateTime('payment_date');
            $table->string('currency');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};