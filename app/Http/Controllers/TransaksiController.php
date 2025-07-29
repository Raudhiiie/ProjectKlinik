<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiDetail;
use App\Models\SubLayanan;
use App\Models\Pasien;
use App\Models\Produk;
use App\Models\Terapis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pasien', 'details.subLayanan.layanan', 'details.produk'])
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('keterangan', $request->status);
        }

        if ($request->nama_layanan) {
            $query->whereHas('details.subLayanan', function ($q) use ($request) {
                $q->where('nama', $request->nama_layanan);
            });
        }

        $transaksi = $query->get();
        $allSubLayanan = SubLayanan::pluck('nama')->unique();

        return view('terapis.transaksi.index', compact('transaksi', 'allSubLayanan'));
    }

    public function create()
    {
        $pasiens = Pasien::orderBy('nama')->get();
        $subLayanan = SubLayanan::with('layanan')->orderBy('nama')->get();
        $subLayananGroup = $subLayanan->groupBy(function ($item) {
            return $item->layanan->nama;
        });
        $produk = Produk::where('posisi', 'cream')
            ->select(DB::raw('MIN(id) as id'), 'nama_produk', DB::raw('MAX(harga) as harga'))
            ->groupBy('nama_produk')
            ->orderBy('nama_produk')
            ->get();


        $terapis = Terapis::all();


        return view('terapis.transaksi.tambah', compact('pasiens', 'subLayananGroup', 'produk', 'terapis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasiens,no_rm',
            'terapis_id' => 'required|exists:terapis,id',
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit',
            'items' => 'required|array|min:1',
            'items.*.jenis' => 'required|in:layanan,produk',
            'items.*.id' => 'required',
            'items.*.jumlah' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {

            // Hitung total
            $total = collect($request->items)->sum(function ($item) {
                if ($item['jenis'] == 'layanan') {
                    $subLayanan = SubLayanan::find($item['id']);
                    if (!$subLayanan) return 0;
                    $harga = $subLayanan->harga;
                } else {
                    $produk = Produk::where('posisi',  'cream')->find($item['id']);
                    if (!$produk) return 0;
                    $harga = $produk->harga;
                }
                return $harga * $item['jumlah'];
            });


            // Buat transaksi
            $transaksi = Transaksi::create([
                'no_rm' => $request->no_rm,
                'terapis_id' => $request->terapis_id,
                'tanggal' => now(),
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan' => 'Belum Bayar',
                'total_harga' => $total
            ]);
            // Simpan detail transaksi
            foreach ($request->items as $item) {
                if ($item['jenis'] == 'layanan') {
                    $subLayanan = SubLayanan::find($item['id']);
                    $harga = $subLayanan->harga;
                    $transaksi->details()->create([
                        'jenis' => 'layanan',
                        'sub_layanan_id' => $subLayanan['id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $harga,
                        'subtotal' => $harga * $item['jumlah']
                    ]);
                } else {
                    $produk = Produk::where('posisi', 'cream')->find($item['id']);

                    if (!$produk || $produk->sisa < $item['jumlah']) {
                        DB::rollback();
                        return redirect()->back()->with('error', 'Stok produk "' . $produk->nama_produk . '" tidak mencukupi. Sisa: ' . $produk->sisa);
                    }

                    // Kurangi stok
                    $produk->sisa -= $item['jumlah'];
                    $produk->out += $item['jumlah'];
                    $produk->save();
                    // Ambil stok terakhir untuk posisi cream dan nama produk yang sama
                    $stokTerakhir = Produk::where('posisi', 'cream')
                        ->whereRaw('LOWER(nama_produk) = ?', [strtolower($produk->nama_produk)])
                        ->orderByDesc('tanggal')
                        ->first();

                    $sisaSebelumnya = $stokTerakhir ? $stokTerakhir->sisa : 0;
                    $sisaBaru = $sisaSebelumnya - $item['jumlah'];
                    $sisaBaru = max($sisaBaru, 0); // biar gak minus

                    // Catat pengeluaran di tabel produk (riwayat)
                    Produk::create([
                        'nama_produk' => $produk->nama_produk,
                        'tanggal' => now(),
                        'in' => 0,
                        'out' => $item['jumlah'],
                        'sisa' => $sisaBaru,
                        'posisi' => 'cream',
                        'terapis_id' => $transaksi->terapis_id ?? null,
                        'harga' => $produk->harga
                    ]);


                    $harga = Produk::find($item['id'])->harga;
                    $transaksi->details()->create([
                        'jenis' => 'produk',
                        'produk_id' => $item['id'],
                        'jumlah' => $item['jumlah'],
                        'harga_satuan' => $harga,
                        'subtotal' => $harga * $item['jumlah']
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('terapis.transaksi.index')->with('success', 'Transaksi berhasil dibuat');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $pasiens = Pasien::orderBy('nama')->get();
        $subLayanan = SubLayanan::orderBy('nama')->get();
        $produks = Produk::orderBy('nama')->get();
        return view('terapis.transaksi.update', compact('transaksi', 'pasiens', 'subLayanan', 'produks'));
    }

    public function update(Request $request, $id)
    {
        // Implementasi mirip `store()`, tetapi update data
        // Bisa saya bantu buatkan juga jika diperlukan
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        if ($transaksi) {
            $transaksi->details()->delete(); // hapus detail dulu
            $transaksi->delete();
            return redirect()->route('terapis.transaksi.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function laporan(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $transaksis = Transaksi::with(['pasien', 'details.subLayanan', 'details.produk'])
            ->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai])
            ->get();

        $totalPendapatan = $transaksis->sum('total_harga');

        return response()->json([
            'success' => true,
            'message' => "Laporan transaksi dari {$request->tanggal_mulai} sampai {$request->tanggal_selesai}",
            'total_pendapatan' => $totalPendapatan,
            'data' => $transaksis,
        ]);
    }

    // TransaksiController.php
    public function bayar(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit',
            'jumlah_bayar' => 'required|numeric|min:0'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $total = $transaksi->total_harga;
        $bayar = $request->jumlah_bayar;

        if ($bayar < $total) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan');
        }

        $transaksi->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_bayar' => $bayar,
            'kembalian' => $bayar - $total,
            'keterangan' => 'Lunas',
            'tanggal_bayar' => now()
        ]);

        return redirect()->route('terapis.transaksi.index')
            ->with('success', 'Pembayaran berhasil dicatat');
    }

    public function konfirmasi(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::findOrFail($id);

            $request->validate([
                'metode_pembayaran' => 'required|in:cash,debit,credit,qris',
                'jumlah_bayar' => 'required|numeric|min:' . $transaksi->total_harga
            ]);

            $transaksi->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'keterangan' => 'Lunas',
                'tanggal_bayar' => now(),
                'jumlah_bayar' => $request->jumlah_bayar,
                'kembalian' => $request->jumlah_bayar - $transaksi->total_harga
            ]);

            DB::commit();

            return redirect()->route('terapis.transaksi.index')
                ->with('success', 'Transaksi berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal mengkonfirmasi: ' . $e->getMessage());
        }
    }

    public function cetak($id)
    {
        $transaksi = Transaksi::with(['pasien', 'details.subLayanan.layanan', 'terapis'])->findOrFail($id);
        return view('terapis.transaksi.cetak', compact('transaksi'));
    }

    // TransaksiController.php

    public function showBayar($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        return view('transaksi.modal_bayar', compact('transaksi'));
    }

    public function prosesBayar(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:cash,transfer,qris,debit',
            'jumlah_bayar' => 'required|numeric|min:0'
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $total = $transaksi->total_harga;
        $bayar = $request->jumlah_bayar;

        if ($bayar < $total) {
            return back()->with('error', 'Jumlah pembayaran kurang dari total tagihan');
        }

        $transaksi->update([
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_bayar' => $bayar,
            'kembalian' => $bayar - $total,
            'keterangan' => 'Lunas',
            'tanggal_bayar' => now()
        ]);

        return redirect()->route('transaksi.index')
            ->with('success', 'Pembayaran berhasil dicatat');
    }

    public function tambahProduk($id)
    {
        $transaksi = Transaksi::with('details')->findOrFail($id);
        $produk = Produk::where('posisi', 'cream')
            ->select(DB::raw('MIN(id) as id'), 'nama_produk', DB::raw('MAX(harga) as harga'))
            ->groupBy('nama_produk')
            ->orderBy('nama_produk')
            ->get();



        return view('terapis.transaksi.tambah_produk', compact('transaksi', 'produk'));
    }

    public function simpanProduk(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah' => 'required|integer|min:1',
        ]);

        $transaksi = Transaksi::findOrFail($id);
        $produk = Produk::where('posisi', 'cream')->findOrFail($request->produk_id);

        if ($produk->sisa < $request->jumlah) {
            return redirect()->back()->with('error', 'Stok produk tidak mencukupi. Sisa: ' . $produk->sisa);
        }

        // Kurangi stok
        $produk->sisa -= $request->jumlah;
        $produk->out += $request->jumlah;
        $produk->save();

        // Ambil stok terakhir untuk posisi cream dan nama produk yang sama
        $stokTerakhir = Produk::where('posisi', 'cream')
            ->whereRaw('LOWER(nama_produk) = ?', [strtolower($produk->nama_produk)])
            ->orderByDesc('tanggal')
            ->first();

        $sisaSebelumnya = $stokTerakhir ? $stokTerakhir->sisa : 0;
        $sisaBaru = $sisaSebelumnya - $request->jumlah;
        $sisaBaru = max($sisaBaru, 0);

        // Catat pengeluaran di tabel produk
        Produk::create([
            'nama_produk' => $produk->nama_produk,
            'tanggal' => now(),
            'in' => 0,
            'out' => $request->jumlah,
            'sisa' => $sisaBaru,
            'posisi' => 'cream',
            'terapis_id' => $transaksi->terapis_id ?? null,
            'harga' => $produk->harga
        ]);

        // Buat detail transaksi
        $subtotal = $produk->harga * $request->jumlah;

        $transaksi->details()->create([
            'jenis' => 'produk',
            'produk_id' => $produk->id,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $produk->harga,
            'subtotal' => $subtotal
        ]);

        // Update total
        $transaksi->calculateTotal();

        return redirect()->route('terapis.transaksi.index')->with('success', 'Produk berhasil ditambahkan ke transaksi.');
    }
}
