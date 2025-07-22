<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLayanan extends Model
{
    use HasFactory;

    protected $table = 'sublayanans';
    
    protected $fillable = [
        'layanan_id',
        'nama',
        'harga'
    ];

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }

    public function rekamMedisDetails()
    {
        return $this->hasMany(RekamMedisDetail::class);
    }
}
