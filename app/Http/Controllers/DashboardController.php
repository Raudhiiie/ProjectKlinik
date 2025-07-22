<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\RekamMedis;
//use App\Models\Dokter;
use App\Models\Terapis;
use App\Models\Antrian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function indexterapis()
    {
        $hari_ini = Pasien::whereDate('created_at', Carbon::today())->count();

        $pasien_bulanan = Pasien::selectRaw('MONTH(created_at) as bulan, COUNT(*) as jumlah')
            ->whereYear('created_at', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $labels_bulan = [];
        $data_bulanan = [];

        foreach ($pasien_bulanan as $item) {
            $labels_bulan[] = Carbon::create()->month($item->bulan)->translatedFormat('F');
            $data_bulanan[] = $item->jumlah;
        }

        // $jumlah_dokter = Dokter::count();
        $jumlah_terapis = Terapis::count();

        $antrian = Antrian::with('pasien')
            ->where('status_panggil', 'Belum Dipanggil')
            ->orderBy('tanggal')
            ->get();


        return view('terapis.dashboard.index', compact(
            'hari_ini',
            'labels_bulan',
            'data_bulanan',
            //'jumlah_dokter',
            'jumlah_terapis',
            'antrian'
        ));
    }

    public function indexdokter()
    {
        $rekam_medis = RekamMedis::count(); 

    $pasien_hari_ini = Antrian::whereDate('tanggal', Carbon::today())->count();

    $pasien_bulan_ini = Antrian::whereMonth('tanggal', Carbon::now()->month)
        ->whereYear('tanggal', Carbon::now()->year)
        ->count();

    // Untuk grafik bulanan
    $pasien_bulanan = Antrian::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
        ->whereYear('tanggal', Carbon::now()->year)
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->get();

    $labels_bulan = [];
    $data_bulanan = [];

    foreach ($pasien_bulanan as $item) {
        $labels_bulan[] = Carbon::create()->month($item->bulan)->translatedFormat('F'); // e.g. Januari, Februari
        $data_bulanan[] = $item->jumlah;
    }

    return view('dokter.dashboard.index', compact(
        'pasien_hari_ini',
        'pasien_bulan_ini',
        'rekam_medis',
        'labels_bulan',
        'data_bulanan'
    ));
    }
}
