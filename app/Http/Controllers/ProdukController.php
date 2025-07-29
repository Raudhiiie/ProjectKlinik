<?php

namespace App\Http\Controllers;


use App\Models\Produk;
use App\Models\Terapis;

use Illuminate\Http\Request;

class ProdukController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    // Menampilkan Produk Gudang
    public function index(Request $request, $posisi)
    {
        // Mulai query
        $query = Produk::where('posisi', $posisi)->orderBy('tanggal', 'desc');

        // Jika ada request filter nama_produk
        if ($request->has('nama_produk') && $request->nama_produk != '') {
            $query->whereRaw('LOWER(nama_produk) = ?', [strtolower($request->nama_produk)]);
        }


        $produk = $query->get();

        // Arahkan ke view sesuai posisi
        if (in_array($posisi, ['gudang', 'cabin', 'cream'])) {
            return view("terapis.produk.$posisi.index", compact('produk', 'posisi'));
        } else {
            abort(404); // jika posisi tidak valid
        }
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create($posisi)
    {
        $terapis = Terapis::all();
        return view('terapis.produk.tambah', compact('posisi', 'terapis'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $posisi)
    {
        $request->validate([
            'nama_produk' => 'required',
            'tanggal' => 'required|date',
            'in' => 'required|integer|min:0',
            'out' => 'required|integer|min:0',
            'posisi' => 'required',
            'terapis_id' => 'nullable|exists:terapis,id',
            'harga' => 'nullable|integer|min:0'
        ]);

        // Jika cabin atau cream, ambil dari gudang
        if (in_array(strtolower($request->posisi), ['cabin', 'cream'])) {
            $stokGudang = Produk::where('posisi', 'gudang')
                ->whereRaw('LOWER(nama_produk) = ?', [strtolower($request->nama_produk)])
                ->orderByDesc('tanggal')
                ->first();

            if (!$stokGudang) {
                return redirect()->back()->with('error', 'Produk "' . $request->nama_produk . '" belum tersedia di Gudang. Silakan tambahkan stok ke Gudang terlebih dahulu.');
            }

            if ($stokGudang->sisa < $request->in) {
                return redirect()->back()->with('error', 'Stok produk "' . $request->nama_produk . '" di Gudang hanya tersedia ' . $stokGudang->sisa . ' unit, tidak cukup untuk transfer ke ' . $request->posisi . '.');
            }


            // Kurangi stok di gudang
            Produk::create([
                'nama_produk' => $request->nama_produk,
                'tanggal' => $request->tanggal,
                'in' => 0,
                'out' => $request->in,
                'sisa' => $stokGudang->sisa - $request->in,
                'posisi' => 'gudang',
                'harga' => $stokGudang->harga, // Harga tetap disimpan untuk catatan historis
            ]);
            $hargaFinal = $stokGudang->harga ?? 0; // fallback kalau kosong
        } else {
            // Jika gudang, ambil dari input user
            $hargaFinal = $request->harga;
        }


        // Hitung sisa posisi target (cabin/cream/gudang)
        $stokTerakhir = Produk::where('posisi', strtolower($request->posisi))
            ->whereRaw('LOWER(nama_produk) = ?', [strtolower($request->nama_produk)])
            ->orderByDesc('tanggal')
            ->first();

        $sisaSebelumnya = $stokTerakhir ? $stokTerakhir->sisa : 0;
        $sisa = $sisaSebelumnya + $request->in - $request->out;

        // Cek data dengan nama_produk, tanggal, dan posisi sudah ada
        $existingProduk = Produk::whereRaw('LOWER(nama_produk) = ?', [strtolower($request->nama_produk)])
            ->where('tanggal', $request->tanggal)
            ->where('posisi', strtolower($request->posisi))
            ->first();

        if ($existingProduk) {
            // Update stok lama
            $existingProduk->in += $request->in;
            $existingProduk->out += $request->out;
            $existingProduk->sisa = $existingProduk->sisa + $request->in - $request->out;
            $existingProduk->sisa = max($existingProduk->sisa, 0); // jangan sampai minus
            $existingProduk->terapis_id = $request->terapis_id ?? $existingProduk->terapis_id;
            $existingProduk->harga = $hargaFinal ?? $existingProduk->harga;
            $existingProduk->save();

            return redirect()->route('terapis.produk.index', $posisi)->with(['success' => 'Data Berhasil Diupdate!']);
        }


        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'tanggal' => $request->tanggal,
            'in' => $request->in,
            'out' => $request->out,
            'sisa' => $sisa < 0 ? 0 : $sisa,
            'posisi' => strtolower($request->posisi),
            'terapis_id' => $request->terapis_id,
            'harga' => $hargaFinal,
        ]);

        if ($produk) {
            return redirect()->route('terapis.produk.index', $posisi)->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('terapis.produk.index', $posisi)->with(['error' => 'Data Gagal Disimpan!']);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($posisi, $id)
    {
        $produk = Produk::findOrFail($id); // pastikan produk ditemukan
        $terapis = Terapis::all();

        return view('terapis.produk.update', compact('produk', 'posisi', 'terapis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $posisi, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required',
            'tanggal' => 'required|date',
            'in' => 'required|integer|min:0',
            'out' => 'required|integer|min:0',
            'posisi' => 'required',
            'terapis_id' => 'nullable|exists:terapis,id',
            'harga' => 'nullable|integer|min:0'
        ]);

        $sisa = $request->in - $request->out;

        $updated = $produk->update([
            'nama_produk' => $request->nama_produk,
            'tanggal' => $request->tanggal,
            'in' => $request->in,
            'out' => $request->out,
            'sisa' => $sisa,
            'posisi' => strtolower($request->posisi),
            'terapis_id' => $request->terapis_id,
            'harga' => $request->harga,
        ]);

        if ($updated) {
            return redirect()->route('terapis.produk.index', ['posisi' => strtolower($posisi)])
                ->with(['success' => 'Data produk berhasil diperbarui!']);
        } else {
            return redirect()->route('terapis.produk.index', ['posisi' => strtolower($posisi)])
                ->with(['error' => 'Gagal memperbarui data produk!']);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($posisi, $id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();

        return redirect()->route('terapis.produk.index', ['posisi' => strtolower($posisi)])->with('success', 'Produk berhasil dihapus.');

        // if ($pasien) {
        //     return redirect()->route('pasien.index')->with(['success' => 'Data Berhasil Dihapus!']);
        // } else {
        //     return redirect()->route('pasien.index')->with(['error' => 'Data Gagal Dihapus!']);
        // }
    }
}
