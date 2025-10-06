<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemRequest extends Model
{
    use HasFactory;

    /**
     * Baris ini memberitahu Laravel untuk menggunakan tabel 'requests'
     * @var string
     */
    protected $table = 'requests'; // <-- INI BAGIAN PALING PENTING

    /**
     * Kolom yang boleh diisi secara massal.
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'item_id',
        'jumlah_request',
        'status',
    ];

    /**
     * Mendefinisikan relasi ke model User.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi ke model Item.
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}