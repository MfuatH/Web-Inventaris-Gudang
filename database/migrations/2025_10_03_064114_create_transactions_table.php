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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id')->nullable()
                ->constrained('request_barang')->nullOnDelete();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->integer('jumlah');
            $table->enum('tipe', ['masuk', 'keluar']);
            $table->date('tanggal');
            $table->timestamps();
        });
    }
        /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
