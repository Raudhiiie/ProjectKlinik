<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id',
        'jenis',
        'layanan_id',
        'produk_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'rekam_medis_id',
        'terapis_id',
    ];

    public function subLayanan()
    {
        return $this->belongsTo(SubLayanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'rekam_medis_id');
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}

