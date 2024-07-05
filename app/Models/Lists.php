<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lists extends Model
{
    use HasFactory;
    protected $fillable = [
        'nopd',
        'tglemail',
        'uraian',
        'rig',
        'departement',
        'jlh',
        'realisasi',
        'selisih',
        'status',
        'tglpembayaran',
        'tglpelunasan',
        'rekening',
        'evidence',
        'keterangan',
    ];
}
