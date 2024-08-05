<?php

use App\Constants\FieldType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('microsite_fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('label', 150);
            $table->enum('type', FieldType::toArray());
            $table->string('validation_rules', 255)->nullable();
            $table->json('options')->nullable();
            $table->boolean('modifiable')->default(false);
            $table->foreignId('microsite_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsite_fields');
    }
};
