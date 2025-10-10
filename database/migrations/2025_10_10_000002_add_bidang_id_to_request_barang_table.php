<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('request_barang') && !Schema::hasColumn('request_barang', 'bidang_id')) {
            Schema::table('request_barang', function (Blueprint $table) {
                $table->foreignId('bidang_id')->nullable()->after('user_id')->constrained('bidang')->cascadeOnDelete();
            });

            // Backfill simple: if request has user_id, try map user's text bidang to table bidang.nama
            if (Schema::hasTable('bidang') && Schema::hasColumn('request_barang', 'user_id')) {
                $rows = DB::table('request_barang')->select('id', 'user_id')->whereNotNull('user_id')->get();
                foreach ($rows as $row) {
                    $user = DB::table('users')->where('id', $row->user_id)->first();
                    if ($user && isset($user->bidang)) {
                        $bidang = DB::table('bidang')->where('nama', $user->bidang)->first();
                        if ($bidang) {
                            DB::table('request_barang')->where('id', $row->id)->update(['bidang_id' => $bidang->id]);
                        }
                    }
                }
            }

            // Enforce not null if possible (ignore if fails)
            try {
                Schema::table('request_barang', function (Blueprint $table) {
                    $table->foreignId('bidang_id')->nullable(false)->change();
                });
            } catch (\Throwable $e) {
                // leave nullable if platform cannot change nullability without DBAL
            }
        }
    }

    public function down()
    {
        if (Schema::hasTable('request_barang') && Schema::hasColumn('request_barang', 'bidang_id')) {
            Schema::table('request_barang', function (Blueprint $table) {
                $table->dropConstrainedForeignId('bidang_id');
            });
        }
    }
};


