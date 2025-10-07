<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_barang',
        'jumlah',
        'satuan', // DITAMBAHKAN: 'satuan' sekarang bisa diisi
        'lokasi',
        'keterangan',
    ];

    /**
     * Get the transactions for the item.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the requests for the item.
     */
    public function requests()
    {
        return $this->hasMany(Request::class);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            if (empty($item->kode_barang)) {
                DB::transaction(function () use ($item) {
                    $latestItem = self::lockForUpdate()->latest('id')->first();
                    $nextId = $latestItem ? $latestItem->id + 1 : 1;
                    $item->kode_barang = 'BRG-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
                });
            }
        });
    }
}
