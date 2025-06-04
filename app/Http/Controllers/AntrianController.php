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
}
