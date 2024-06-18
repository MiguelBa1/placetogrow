<?php

use App\Constants\CurrencyType;
use App\Constants\MicrositeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('payment_currency', array_column(CurrencyType::cases(), 'value'));
            $table->integer('payment_expiration');
            $table->enum('type', array_column(MicrositeType::cases(), 'value'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
