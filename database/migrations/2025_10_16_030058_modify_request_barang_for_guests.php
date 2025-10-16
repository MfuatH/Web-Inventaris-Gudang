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
        Schema::table('request_barang', function (Blueprint $table) {
            // Langkah 1: Hapus foreign key constraint
            // Nama constraint bisa bervariasi, cek nama constraint di DB Anda.
            // Biasanya formatnya: namatabel_namakolom_foreign
            $table->dropForeign(['user_id']);

            // Langkah 2: Hapus kolom user_id
            $table->dropColumn('user_id');

            // Langkah 3 (Rekomendasi): Ubah kolom nama_pemohon agar tidak bisa null
            $table->string('nama_pemohon')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_barang', function (Blueprint $table) {
            // Kode untuk mengembalikan perubahan jika migrasi di-rollback
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('nama_pemohon')->nullable()->change();
        });
    }
};