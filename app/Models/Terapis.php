<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terapis extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'alamat',
        'no_hp',
        'tanggal_lahir',
        'tanggal_bergabung',
    ];

    public function tindakans()
    {
        return $this->hasMany(Tindakan::class);
    }

    public function produks()
    {
        return $this->hasMany(Produk::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    public function details()
    {
        return $this->hasMany(TransaksiDetail::class);
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class);
    }
}
