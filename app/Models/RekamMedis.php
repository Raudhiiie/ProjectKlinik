<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_rm',
        'tanggal',
        'keluhan',
        'obat',
        'tindakan',
        'terapis_id',
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'no_rm', 'no_rm');
    }

    public function details()
    {
        return $this->hasMany(RekamMedisDetail::class);
    }

    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }
    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function sublayanan()
    {
        return $this->belongsTo(SubLayanan::class);
    }

    public function transaksi()
    {
        return $this->hasOne(Transaksi::class);
    }
}
