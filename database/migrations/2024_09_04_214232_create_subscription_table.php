<?php

use App\Constants\CurrencyType;
use App\Constants\SubscriptionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', array_column(SubscriptionStatus::cases(), 'value'))->default(SubscriptionStatus::PENDING);
            $table->string('reference', 50)->unique();
            $table->string('description', 100);
            $table->string('request_id', 50)->nullable();
            $table->string('status_message', 255)->nullable();
            $table->enum('currency', array_column(CurrencyType::cases(), 'value'));
            $table->text('token')->nullable();
            $table->text('subtoken')->nullable();
            $table->text('additional_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};
