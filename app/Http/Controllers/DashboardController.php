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
        setlocale(LC_TIME, 'id_ID.UTF-8');
        Carbon::setLocale('id');

        $hari_ini = Antrian::whereDate('tanggal', today())
            ->where('status',  'selesai')
            ->count('no_rm');


        // Pasien bulanan
        $pasien_bulanan = Antrian::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal', now()->year)
            ->where('status', 'selesai') // hanya pasien yang sudah selesai
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();


        $bulan_labels = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $data_pasien = $pasien_bulanan->pluck('jumlah', 'bulan'); // [bulan => jumlah]

        $labels_bulan = [];
        $data_bulanan = [];

        foreach ($bulan_labels as $no => $nama) {
            $labels_bulan[] = $nama;
            $data_bulanan[] = $data_pasien[$no] ?? 0;
        }


        // Pasien harian dalam 7 hari terakhir
        $rangeTanggal = Carbon::now()->subDays(6)->startOfDay(); // 6 hari ke belakang + hari ini = 7 hari

        $pasien_harian = Antrian::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah')
            ->where('status', 'selesai')
            ->where('tanggal', '>=', $rangeTanggal)
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->keyBy('tanggal');



        $labels_hari = [];
        $data_harian = [];

        for ($i = 6; $i >= 0; $i--) {
            $tanggal = now()->subDays($i)->toDateString();
            $labels_hari[] = Carbon::parse($tanggal)->translatedFormat('l'); // contoh: Senin
            $data_harian[] = $pasien_harian[$tanggal]->jumlah ?? 0;
        }

        $jumlah_terapis = Terapis::count();

        $antrian = Antrian::with('pasien')
            ->whereIn('status', ['menunggu', 'konsultasi', 'proses'])
            ->whereIn('status_panggil',  ['Belum Dipanggil', 'Konsultasi', 'Dipanggil'])
            ->whereDate('tanggal', today())
            ->orderBy('tanggal')
            ->get();



        return view('terapis.dashboard.index', compact(
            'hari_ini',
            'labels_bulan',
            'data_bulanan',
            'labels_hari',
            'data_harian',
            'jumlah_terapis',
            'antrian'
        ));
    }


    public function indexdokter()
    {

        setlocale(LC_TIME, 'id_ID.UTF-8');
        Carbon::setLocale('id');

        $rekam_medis = RekamMedis::count();

        $pasien_hari_ini = Antrian::whereDate('tanggal', Carbon::today())->count();

        $pasien_bulan_ini = Antrian::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('status', 'selesai')
            ->count();


        // Grafik bulanan
        $pasien_bulanan = Antrian::selectRaw('MONTH(tanggal) as bulan, COUNT(*) as jumlah')
            ->whereYear('tanggal', now()->year)
            ->where('status', 'selesai')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        $bulan_labels = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        $data_pasien = $pasien_bulanan->pluck('jumlah', 'bulan'); // [bulan => jumlah]

        $labels_bulan = [];
        $data_bulanan = [];

        foreach ($bulan_labels as $no => $nama) {
            $labels_bulan[] = $nama;
            $data_bulanan[] = $data_pasien[$no] ?? 0;
        }



        // Buat 7 hari terakhir (termasuk hari ini)
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels_hari[$tanggal] = Carbon::parse($tanggal)->translatedFormat('l'); // Senin, Selasa, dst
            $data_harian[$tanggal] = 0; // default 0 dulu
        }

        // Ambil data dari database
        $pasien_harian = Antrian::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah')
            ->where('status', 'selesai')
            ->whereBetween('tanggal', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Isi data dari query ke array
        foreach ($pasien_harian as $item) {
            $data_harian[$item->tanggal] = $item->jumlah;
        }

        // Siapkan array akhir untuk chart
        $labels_hari_final = array_values($labels_hari); // hanya label hari
        $data_harian_final = array_values($data_harian); // hanya jumlah pasien

        return view('dokter.dashboard.index', compact(
            'pasien_hari_ini',
            'pasien_bulan_ini',
            'rekam_medis',
            'labels_bulan',
            'data_bulanan',
            'labels_hari_final',
            'data_harian_final'
        ));
    }
}
