<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('users') && !Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('no_hp')->nullable()->after('email');
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'no_hp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('no_hp');
            });
        }
    }
};


