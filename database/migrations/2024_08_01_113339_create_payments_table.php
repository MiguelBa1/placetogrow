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
            $table->foreignId('microsite_id')->constrained('microsites')->onDelete('cascade');
            $table->string('reference')->unique();
            $table->string('description');
            $table->string('currency');
            $table->integer('amount');
            $table->string('status')->nullable();
            $table->string('status_message')->nullable();
            $table->string('request_id')->nullable();
            $table->string('payment_method_name')->nullable();
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
