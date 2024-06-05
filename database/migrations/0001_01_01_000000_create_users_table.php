<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->nullable();
            $table->string('nombre')->nullable();
            $table->string('apellido')->nullable();
            $table->string('tipo_de_documento')->nullable();
            $table->string('numero_de_documento')->nullable();
            $table->string('telefono')->nullable();
            $table->string('moneda')->nullable();
            $table->integer('valor')->nullable();

            $table->string('payment_reference')->nullable();
            $table->string('request_id')->nullable();
            $table->string('process_url')->nullable();
            $table->dateTime('expires_in')->nullable();
            $table->string('internal_reference')->nullable();
            $table->string('franchise')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_method_name')->nullable();
            $table->string('issuer_name')->nullable();
            $table->string('receipt')->nullable();
            $table->string('authorization')->nullable();
            $table->string('status')->nullable();
            $table->string('status_message')->nullable();
            $table->dateTime('payment_date')->nullable();

            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
