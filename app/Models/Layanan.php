<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama',
        'harga',
        'jumlah',
    ];

    public function getTotalBiayaAttribute()
    {
        return $this->harga * $this->jumlah;
    }

    public function sublayanans()
    {
        return $this->hasMany(Sublayanan::class);
    }
}
