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
        'terapis_id',
        'tanggal',
        'jenis',
        'metode_pembayaran',
        'jumlah',
        'total_harga',
        'keterangan',
        'rekam_medis_id',
    ];

     public function calculateTotal()
    {
        $this->total_harga = $this->details->sum('subtotal');
        $this->save();
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    public function subLayanan()
    {
        return $this->belongsTo(SubLayanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class);
    }

    // Transaksi.php (Model)
    public function layanan()
    {
        return $this->belongsToMany(Layanan::class, 'layanan_transaksi', 'transaksi_id', 'layanan_id');
    }
}
