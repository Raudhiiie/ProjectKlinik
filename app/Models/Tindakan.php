<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tindakan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_pasien',
        'jenis_tindakan',
        'jumlah',
        'tanggal',
    ];
    public function terapis()
    {
        return $this->belongsTo(Terapis::class);
    }
}

