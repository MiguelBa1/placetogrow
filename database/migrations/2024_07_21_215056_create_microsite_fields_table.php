<?php

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
            $table->string('type', 50);
            $table->string('validation_rules')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('microsite_fields');
    }
};
