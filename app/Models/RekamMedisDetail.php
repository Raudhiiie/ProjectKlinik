<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedisDetail extends Model
{
    use HasFactory;
    protected $fillable = ['rekam_medis_id', 'sublayanan_id', 'harga'];

    public function sublayanan()
    {
        return $this->belongsTo(Sublayanan::class);
    }

    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class);
    }
}
