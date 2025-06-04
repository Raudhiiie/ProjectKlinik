<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pasien extends Model
{
    use HasFactory;
    protected $table = 'pasiens'; 
    protected $primaryKey = 'no_rm'; //kolom no_rm sebagai primary key
    public $incrementing = false; //no_rm bukan auto increment
    protected $keyType = 'string'; //no_rm bertipe string
    protected $fillable = [
        'no_rm',
        'nama',
        'nik',
        'alamat',
        'pekerjaan',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
    ];

    public function antrians()
    {
        return $this->hasMany(Antrian::class, 'pasien_id', 'no_rm');
    }
}
