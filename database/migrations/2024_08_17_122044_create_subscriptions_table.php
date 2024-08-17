<?php

use App\Constants\BillingUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained('microsites')->onDelete('cascade');
            $table->integer('price');
            $table->integer('total_duration');
            $table->integer('billing_frequency')->default(1);
            $table->enum('billing_unit', array_column(BillingUnit::cases(), 'value'))->default(BillingUnit::MONTHS->value);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
