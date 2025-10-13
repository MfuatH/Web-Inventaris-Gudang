<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];
}


