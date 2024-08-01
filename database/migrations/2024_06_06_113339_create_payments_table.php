<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained('guests')->onDelete('cascade');
            $table->string('reference');
            $table->string('description');
            $table->string('currency');
            $table->integer('amount');
            $table->string('status')->nullable();
            $table->string('status_message')->nullable();
            $table->dateTime('expires_in')->nullable();
            $table->string('request_id')->nullable();
            $table->string('process_url')->nullable();
            $table->string('internal_reference')->nullable();
            $table->string('franchise')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('receipt')->nullable();
            $table->string('authorization')->nullable();
            $table->dateTime('payment_date')->nullable();
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
