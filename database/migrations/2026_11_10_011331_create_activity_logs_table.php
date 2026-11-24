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
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
        $table->string('user_nama')->default('Sistem');
        $table->string('aksi', 20);   // dibuat, diubah, dihapus
        $table->string('objek', 20);  // sekolah, profil
        // sengaja bukan foreign key: log tetap ada setelah sekolah dihapus
        $table->unsignedBigInteger('sekolah_id')->nullable()->index();
        $table->string('sekolah_nama')->nullable();
        $table->json('perubahan')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('activity_logs');
}
};
