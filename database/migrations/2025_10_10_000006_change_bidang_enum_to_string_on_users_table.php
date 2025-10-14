<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users')) {
            // Ganti tipe kolom bidang menjadi VARCHAR agar fleksibel mengikuti tabel bidang
            // Menggunakan SQL langsung untuk kompatibilitas tanpa doctrine/dbal
            DB::statement("ALTER TABLE `users` MODIFY `bidang` VARCHAR(191) NULL");
        }
    }

    public function down()
    {
        // Opsi rollback: kembalikan ke ENUM default yang lama jika diperlukan
        // Sesuaikan daftar enum sesuai skema awal project Anda
        if (Schema::hasTable('users')) {
            DB::statement("ALTER TABLE `users` MODIFY `bidang` ENUM('sekretariat','psda','irigasi','swp','binfat') NULL");
        }
    }
};


