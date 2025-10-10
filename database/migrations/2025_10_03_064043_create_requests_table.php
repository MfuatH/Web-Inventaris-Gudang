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
        // Handle three cases: fresh install, existing 'requests', or already renamed 'request_barang'
        if (Schema::hasTable('request_barang')) {
            Schema::table('request_barang', function (Blueprint $table) {
                if (!Schema::hasColumn('request_barang', 'nama_pemohon')) {
                    $table->string('nama_pemohon')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('request_barang', 'nip')) {
                    $table->string('nip')->nullable()->after('nama_pemohon');
                }
                if (!Schema::hasColumn('request_barang', 'no_hp')) {
                    $table->string('no_hp')->after('nip');
                }
                if (Schema::hasColumn('request_barang', 'user_id')) {
                    // make nullable when possible (requires doctrine/dbal)
                    try { $table->foreignId('user_id')->nullable()->change(); } catch (\Throwable $e) { /* ignore if platform doesn't support change */ }
                }
            });
        } elseif (Schema::hasTable('requests')) {
            Schema::rename('requests', 'request_barang');
            Schema::table('request_barang', function (Blueprint $table) {
                if (!Schema::hasColumn('request_barang', 'nama_pemohon')) {
                    $table->string('nama_pemohon')->nullable()->after('user_id');
                }
                if (!Schema::hasColumn('request_barang', 'nip')) {
                    $table->string('nip')->nullable()->after('nama_pemohon');
                }
                if (!Schema::hasColumn('request_barang', 'no_hp')) {
                    $table->string('no_hp')->after('nip');
                }
                if (Schema::hasColumn('request_barang', 'user_id')) {
                    try { $table->foreignId('user_id')->nullable()->change(); } catch (\Throwable $e) { /* ignore */ }
                }
            });
        } else {
            // Fresh install: create the final table schema directly
            Schema::create('request_barang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
                $table->foreignId('item_id')->constrained()->onDelete('cascade');
                $table->string('nama_pemohon')->nullable();
                $table->string('nip')->nullable();
                $table->string('no_hp');
                $table->integer('jumlah_request');
                $table->enum('status', ['pending', 'approved', 'rejected', 'received'])->default('pending');
                $table->timestamps();
            });
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('request_barang')) {
            if (!Schema::hasTable('requests')) {
                Schema::rename('request_barang', 'requests');
            } else {
                Schema::dropIfExists('request_barang');
            }
        }
    }
};
