<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $fillable = [
        'no_rm',
        'sub_layanan_id',
        'tanggal',
        'jenis',
        'metode_pembayaran',
        'jumlah',
        'total_harga',
        'keterangan',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    public function subLayanan()
    {
        return $this->belongsTo(SubLayanan::class);
    }
}
