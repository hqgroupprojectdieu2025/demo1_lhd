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
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('fullname', 255);
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('avatar', 255)->nullable();
            $table->string('phone', 15)->nullable();
            $table->date('dob')->nullable();
            $table->string('address', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('account_type')->default(1);
            $table->boolean('two_fa_enable')->default(false);
            $table->string('two_fa_secret')->nullable();
            $table->string('recovery_code')->nullable();
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('user');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
    }
};
