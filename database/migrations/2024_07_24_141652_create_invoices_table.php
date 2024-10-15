<?php

use App\Constants\DocumentType;
use App\Constants\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->string('reference', 100);
            $table->enum('document_type', array_column(DocumentType::cases(), 'value'));
            $table->string('document_number', 20);
            $table->string('name', 100);
            $table->string('last_name', 100);
            $table->string('email', 100);
            $table->enum('status', array_column(InvoiceStatus::cases(), 'value'))->default(InvoiceStatus::PENDING);
            $table->string('phone', 20);
            $table->decimal('amount', 10);
            $table->decimal('late_fee', 10)->nullable();
            $table->decimal('total_amount', 10)->nullable();
            $table->date('expiration_date');
            $table->unique(['microsite_id', 'reference']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
