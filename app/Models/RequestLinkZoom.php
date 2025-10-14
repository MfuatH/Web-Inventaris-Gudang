<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bidang;

class RequestLinkZoom extends Model
{
    use HasFactory;

    protected $table = 'request_linkzoom';

    protected $fillable = [
        'nama_pemohon',
        'nip',
        'no_hp',
        'bidang_id',
        'link_zoom',
        'jadwal_mulai',
        'jadwal_selesai',
        'keterangan',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'jadwal_mulai' => 'datetime',
        'jadwal_selesai' => 'datetime',
    ];

    /**
     * Get the bidang that owns the request.
     */
    public function bidang()
    {
        return $this->belongsTo(Bidang::class, 'bidang_id', 'id');
    }
}


