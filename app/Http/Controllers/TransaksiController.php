<?php

namespace App\Http\Controllers;


use App\Models\Transaksi;
use App\Models\SubLayanan;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function simpanTransaksi(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasiens,no_rm',
            'sub_layanan_id' => 'required|exists:sublayanans,id',
            'tanggal' => 'required|date',
            'jenis' => 'required|string',
            'metode_pembayaran' => 'required|string',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ]);

        $subLayanan = SubLayanan::find($request->sub_layanan_id);

        $totalHarga = $subLayanan->harga * $request->jumlah;

        $transaksi = Transaksi::create([
            'no_rm'    => $request->no_rm,
            'sub_layanan_id' => $request->sub_layanan_id,
            'tanggal' => $request->tanggal,
            'jenis' => $request->jenis,
            'metode_pembayaran' => $request->metode_pembayaran,
            'keterangan' => $request->keterangan,
            'jumlah' => $request->jumlah,
            'total_harga' => $totalHarga,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaksi,
        ]);
    }

    // Contoh method untuk melihat semua transaksi (optional)
    public function index()
    {
        $transaksi = Transaksi::with(['pasien', 'subLayanan'])->get();

        return response()->json([
            'success' => true,
            'message' => 'List semua transaksi',
            'data' => $transaksi,
        ]);
    }

    public function laporan(Request $request)
{
    // Validasi input tanggal
    $request->validate([
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
    ]);

    $tanggalMulai = $request->tanggal_mulai;
    $tanggalSelesai = $request->tanggal_selesai;

    // Query transaksi dengan relasi pasien dan sub layanan, filter berdasarkan tanggal
    $transaksis = Transaksi::with(['pasien', 'subLayanan'])
        ->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai])
        ->get();

    // Hitung total pendapatan
    $totalPendapatan = $transaksis->sum('total_harga');

    return response()->json([
        'success' => true,
        'message' => "Laporan transaksi dari $tanggalMulai sampai $tanggalSelesai",
        'total_pendapatan' => $totalPendapatan,
        'data' => $transaksis,
    ]);
}
}
