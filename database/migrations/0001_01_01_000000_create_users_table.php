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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Kolom Kustom Anda dari Proyek Lama
            $table->string('npm')->unique()->nullable(); // Jadikan nullable jika tidak semua user punya NPM (misal Admin)
            // Gunakan definisi enum yang sudah final (termasuk 'Aslab')
            $table->enum('role', ['Admin', 'Staff', 'Aslab', 'Mahasiswa'])->default('Mahasiswa');

            // Kolom dari Jetstream (dan Laravel standar)
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable(); // Untuk fitur Teams Jetstream
            $table->string('profile_photo_path', 2048)->nullable(); // Untuk fitur Profile Photo Jetstream
            $table->timestamps();
        });

        // Tabel password_reset_tokens dan sessions biarkan seperti yang dibuat oleh Jetstream/Laravel standar
        // (Biasanya ada di file migrasi terpisah atau di file migrasi user awal ini juga)
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