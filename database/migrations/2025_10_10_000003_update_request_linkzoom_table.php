<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('request_linkzoom')) {
            Schema::table('request_linkzoom', function (Blueprint $table) {
                if (Schema::hasColumn('request_linkzoom', 'instansi')) {
                    $table->dropColumn('instansi');
                }
                if (!Schema::hasColumn('request_linkzoom', 'nip')) {
                    $table->string('nip')->nullable()->after('nama_pemohon');
                }
                if (!Schema::hasColumn('request_linkzoom', 'bidang_id')) {
                    $table->foreignId('bidang_id')->nullable()->after('no_hp')->constrained('bidang')->cascadeOnDelete();
                }
                if (!Schema::hasColumn('request_linkzoom', 'link_zoom')) {
                    $table->string('link_zoom')->nullable()->after('bidang_id');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('request_linkzoom')) {
            Schema::table('request_linkzoom', function (Blueprint $table) {
                if (Schema::hasColumn('request_linkzoom', 'link_zoom')) {
                    $table->dropColumn('link_zoom');
                }
                if (Schema::hasColumn('request_linkzoom', 'bidang_id')) {
                    $table->dropConstrainedForeignId('bidang_id');
                }
                if (Schema::hasColumn('request_linkzoom', 'nip')) {
                    $table->dropColumn('nip');
                }
                if (!Schema::hasColumn('request_linkzoom', 'instansi')) {
                    $table->string('instansi')->nullable()->after('nama_pemohon');
                }
            });
        }
    }
};


