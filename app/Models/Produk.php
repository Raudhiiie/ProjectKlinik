<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_produk',
        'tanggal',
        'in',
        'out',
        'sisa',
        'posisi',
        'terapis_id',
        'harga'
    ];

    public function terapis()
{
    return $this->belongsTo(Terapis::class);
}

public function transaksiDetails()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

}