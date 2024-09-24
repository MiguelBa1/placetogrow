<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plan_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained('plans')->onDelete('cascade');
            $table->string('locale', 2);
            $table->string('name', 100);
            $table->text('description');
            $table->timestamps();

            $table->unique(['plan_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_translations');
    }
};
