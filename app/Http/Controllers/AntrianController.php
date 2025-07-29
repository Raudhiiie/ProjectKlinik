<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function daftarAntrian(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasiens,no_rm',
        ]);

        $tanggalHariIni = Carbon::today()->toDateString();

        // Hitung no antrian hari ini
        $jumlahAntrian = Antrian::where('tanggal', $tanggalHariIni)->count();
        $noAntrianBaru = $jumlahAntrian + 1;

        $antrian = Antrian::create([
            'no_rm'    => $request->no_rm,
            'no_antrian'   => $noAntrianBaru,
            'tanggal'      => $tanggalHariIni,
            'status'       => 'menunggu',
            'waktu_daftar' => Carbon::now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil dibuat',
            'data' => $antrian
        ], 201);
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasiens,no_rm',
        ]);

        // $tanggalDipilih = Carbon::parse($request->tanggal)->toDateString();

        $tanggalHariIni = Carbon::today()->toDateString();

        // // Cek apakah pasien sudah antri hari ini
        // $cek = Antrian::where('no_rm', $request->no_rm)
        //     ->where('tanggal', $tanggalHariIni)
        //     ->first();

        // if ($cek) {
        //     return redirect()->back()->with('success', 'Pasien sudah masuk antrian hari ini.');
        // }

        // Hitung no antrian hari ini
        $jumlahAntrian = Antrian::where('tanggal', $tanggalHariIni)->count();
        $noAntrianBaru = $jumlahAntrian + 1;

        Antrian::create([
            'no_rm' => $request->no_rm,
            'no_antrian' => $noAntrianBaru,
            'tanggal' => $tanggalHariIni,
            'status' => 'menunggu',
            'status_panggil' => 'Belum Dipanggil',
            'waktu_daftar' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Pasien berhasil dimasukkan ke antrian.');
    }


    public function panggilPasien($id)
    {
        $antrian = Antrian::findOrFail($id);
        $antrian->status_panggil = 'Dipanggil';
        $antrian->status = 'proses'; // jika kamu ingin langsung ubah ke 'proses'
        $antrian->save();

        return redirect()->route('terapis.dashboard.index')->with('success', 'Pasien telah dipanggil.');
    }


    // Ubah status antrian
    public function ubahStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,proses,selesai,batal',
        ]);

        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json([
                'success' => false,
                'message' => 'Antrian tidak ditemukan',
            ], 404);
        }

        $antrian->status = $request->status;
        $antrian->save();

        return response()->json([
            'success' => true,
            'message' => 'Status antrian berhasil diubah',
            'data' => $antrian
        ]);
    }

    public function lihatAntrianHariIni()
    {
        $tanggalHariIni = Carbon::today()->toDateString();

        $antrians = Antrian::with('pasien')
            ->where('tanggal', $tanggalHariIni)
            ->orderBy('no_antrian')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $antrians
        ]);
    }

    // Hapus antrian
    public function hapusAntrian($id)
    {
        $antrian = Antrian::find($id);

        if (!$antrian) {
            return response()->json([
                'success' => false,
                'message' => 'Antrian tidak ditemukan',
            ], 404);
        }

        $antrian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Antrian berhasil dihapus',
        ]);
    }

    public function monitor()
    {
        $today = Carbon::today()->toDateString();

        $antrianDipanggil = Antrian::whereDate('tanggal', today())
            ->where('status', 'proses')
            ->orderByDesc('updated_at')
            ->first();

        $antrianSelanjutnya = Antrian::whereDate('tanggal', today())
            ->where('status', 'menunggu')
            ->where('status_panggil', 'Belum Dipanggil')
            ->orderBy('no_antrian')
            ->take(5)
            ->get();

        $antrianTerakhirSelesai = Antrian::where('status', 'selesai')
            ->orderBy('updated_at', 'desc')
            ->first();

        return view('monitor.antrian', compact('antrianDipanggil', 'antrianSelanjutnya', 'antrianTerakhirSelesai'));
    }

    public function selesai($id)
    {
        $antrian = Antrian::findOrFail($id);
        // Mengubah status jadi selesai
        $antrian->status = 'selesai';
        $antrian->status_panggil = 'Selesai';
        $antrian->save();

        return redirect()->route('terapis.dashboard.index')->with('success', 'Antrian telah diselesaikan.');
        
    }
}
