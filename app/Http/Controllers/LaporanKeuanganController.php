<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanKeuanganController extends Controller
{
    public function index(Request $request)
{
    $query = Transaksi::select(
        DB::raw('DATE(tanggal) as tanggal'),
        DB::raw("SUM(CASE WHEN metode_pembayaran = 'transfer' THEN total_harga ELSE 0 END) AS tf"),
        DB::raw("SUM(CASE WHEN metode_pembayaran = 'qris' THEN total_harga ELSE 0 END) AS qris"),
        DB::raw("SUM(CASE WHEN metode_pembayaran = 'cash' THEN total_harga ELSE 0 END) AS cash"),
        DB::raw("SUM(total_harga) as pendapatan")
    );

    // Jika ada filter tanggal
    if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
        $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
    }

    $transaksiPerTanggal = $query
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'asc')
        ->get();

    $totalPendapatan = $transaksiPerTanggal->sum('pendapatan');
    $totalPengeluaran = 0;

    return view('terapis.laporanKeuangan.index', compact(
        'transaksiPerTanggal',
        'totalPendapatan',
        'totalPengeluaran'
    ));
}

}
