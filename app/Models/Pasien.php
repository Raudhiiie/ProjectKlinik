<?php

namespace App\Models;
use App\Models\RekamMedis;

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
        return $this->hasMany(Antrian::class, 'no_rm', 'no_rm');
    }

    public function getRouteKeyName()
    {
        return 'no_rm';
    }
    public function rekamMedis()
{
    return $this->hasMany(RekamMedis::class, 'no_rm', 'no_rm');
}

}
