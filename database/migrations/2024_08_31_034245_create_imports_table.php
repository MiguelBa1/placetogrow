<?php

use App\Constants\ImportStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->string('filename', 100);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', array_column(ImportStatus::cases(), 'value'))->default(ImportStatus::PENDING->value);
            $table->json('errors')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imports');
    }
};
