<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'satuan',
        'jumlah',
        'lokasi',
        'keterangan',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            // Dapatkan ID terakhir
            $latestItem = self::latest('id')->first();
            $nextId = $latestItem ? $latestItem->id + 1 : 1;
            // Buat kode barang BRG-0001
            $item->kode_barang = 'BRG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
        });
    }
}