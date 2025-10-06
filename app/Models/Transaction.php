<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_id',
        'request_id',
        'jumlah',
        'tipe',
        'tanggal',
    ];

    /**
     * Mendefinisikan relasi ke model Item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Mendefinisikan relasi ke model ItemRequest.
     */
    public function request()
    {
        return $this->belongsTo(ItemRequest::class);
    }
}