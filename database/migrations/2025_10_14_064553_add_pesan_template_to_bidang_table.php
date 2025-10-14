<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Method up() dijalankan saat kita menjalankan "php artisan migrate"
        Schema::table('bidang', function (Blueprint $table) {
            // Menambahkan kolom baru 'pesan_template' dengan tipe TEXT
            // Tipe TEXT dipilih agar bisa menampung pesan yang panjang.
            // ->nullable() berarti kolom ini boleh kosong.
            // ->after('nama') menempatkan kolom ini setelah kolom 'nama' agar rapi.
            $table->text('pesan_template')->nullable()->after('nama');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Method down() dijalankan saat kita melakukan rollback migrasi
        Schema::table('bidang', function (Blueprint $table) {
            // Menghapus kolom 'pesan_template' jika migrasi dibatalkan
            $table->dropColumn('pesan_template');
        });
    }
};