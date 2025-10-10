<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('bidang')) {
            Schema::create('bidang', function (Blueprint $table) {
                $table->id();
                $table->string('nama');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('request_linkzoom')) {
            Schema::create('request_linkzoom', function (Blueprint $table) {
                $table->id();
                $table->string('nama_pemohon');
                $table->string('nip')->nullable();
                $table->string('no_hp');
                $table->foreignId('bidang_id')->nullable()->constrained('bidang')->cascadeOnDelete();
                $table->string('link_zoom')->nullable();
                $table->dateTime('jadwal_mulai');
                $table->dateTime('jadwal_selesai')->nullable();
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('request_linkzoom');
        Schema::dropIfExists('bidang');
    }
};


