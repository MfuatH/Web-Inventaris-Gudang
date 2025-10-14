<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('request_linkzoom') && !Schema::hasColumn('request_linkzoom', 'status')) {
            Schema::table('request_linkzoom', function (Blueprint $table) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('keterangan');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('request_linkzoom') && Schema::hasColumn('request_linkzoom', 'status')) {
            Schema::table('request_linkzoom', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
