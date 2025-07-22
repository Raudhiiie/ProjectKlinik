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
            $query->where('nama_produk', $request->nama_produk);
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

        $sisa = $request->in - $request->out;

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'tanggal' => $request->tanggal,
            'in' => $request->in,
            'out' => $request->out,
            'sisa' => $sisa,
            'posisi' => strtolower($request->posisi),
            'terapis_id' => $request->terapis_id,
            'harga' => $request->harga,
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
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        if ($produk) {
            return response()->json([
                'status' => 200,
                'success' => true,
                'message' => 'Data Berhasil Dihapus!',
            ], 200);
        } else {
            return response()->json([
                'status' => 500,
                'success' => false,
                'message' => 'Data Berhasil Dihapus!',
            ], 500);
        }

        // if ($pasien) {
        //     return redirect()->route('pasien.index')->with(['success' => 'Data Berhasil Dihapus!']);
        // } else {
        //     return redirect()->route('pasien.index')->with(['error' => 'Data Gagal Dihapus!']);
        // }
    }
}
