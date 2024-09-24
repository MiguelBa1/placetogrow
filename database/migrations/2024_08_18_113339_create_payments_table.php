<?php

use App\Constants\CurrencyType;
use App\Constants\PaymentStatus;
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
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('microsite_id')->constrained('microsites')->onDelete('cascade');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('cascade');
            $table->foreignId('plan_id')->nullable()->constrained('plans')->onDelete('cascade');
            $table->string('reference', 50)->unique();
            $table->string('description', 255);
            $table->enum('currency', array_column(CurrencyType::cases(), 'value'));
            $table->integer('amount');
            $table->enum('status', array_column(PaymentStatus::cases(), 'value'))->default(PaymentStatus::PENDING);
            $table->string('status_message', 255)->nullable();
            $table->string('request_id', 50)->nullable();
            $table->string('payment_method_name', 50)->nullable();
            $table->string('authorization', 50)->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->json('additional_data')->nullable();
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
