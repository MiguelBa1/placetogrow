<?php

use App\Constants\CurrencyType;
use App\Constants\DocumentType;
use App\Constants\MicrositeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('microsites', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('slug', 200)->unique();
            $table->foreignId('category_id')->constrained('categories');
            $table->enum('payment_currency', array_column(CurrencyType::cases(), 'value'));
            $table->integer('payment_expiration')->nullable();
            $table->enum('type', array_column(MicrositeType::cases(), 'value'));
            $table->string('responsible_name', 100);
            $table->string('responsible_document_number', 20);
            $table->enum('responsible_document_type', array_column(DocumentType::cases(), 'value'));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsites');
    }
};
