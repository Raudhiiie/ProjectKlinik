<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\SubLayanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::with('subLayanans')->get();
        return view('terapis.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('terapis.layanan.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string',
            'sublayanans.*.nama' => 'required|string',
            'sublayanans.*.harga' => 'required|integer',
        ]);

        // Simpan layanan
        $layanan = Layanan::create([
            'nama' => $request->nama_layanan,
        ]);

        // Simpan sublayanan
        foreach ($request->sublayanans as $sub) {
            $layanan->sublayanans()->create([
                'nama' => $sub['nama'],
                'harga' => $sub['harga'],
            ]);
        }

        return redirect()->route('terapis.layanan.index')->with('success', 'Layanan dan sublayanan berhasil ditambahkan.');
    }


    // Update layanan utama
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|unique:layanans,nama,' . $id,
        ]);

        $layanan = Layanan::find($id);
        if (!$layanan) {
            return redirect()->route('terapis.layanan.index')->with('error', 'Layanan tidak ditemukan.');
        }

        $layanan->nama = $request->nama;
        $layanan->save();

        return redirect()->route('terapis.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }


    // Hapus layanan utama
    public function destroy($id)
    {
        $layanan = Layanan::find($id);
        if (!$layanan) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        $layanan->delete();

        return redirect()->route('terapis.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    public function createSubLayanan($layanan_id)
    {
        $layanan = Layanan::findOrFail($layanan_id);
        return view('terapis.layanan.tambah_sublayanan', compact('layanan'));
    }

    public function storeSubLayanan(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanans,id',
            'nama' => 'required|string',
            'harga' => 'required|integer',
        ]);

        SubLayanan::create([
            'layanan_id' => $request->layanan_id,
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        return redirect()->route('terapis.layanan.index')->with('success', 'Sub layanan berhasil ditambahkan.');
    }


    // Update sub-layanan
    public function updateSubLayanan(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|integer|min:0',
        ]);

        $subLayanan = SubLayanan::find($id);
        if (!$subLayanan) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-layanan tidak ditemukan',
            ], 404);
        }

        $subLayanan->nama = $request->nama;
        $subLayanan->harga = $request->harga;
        $subLayanan->save();

        return redirect()->route('terapis.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    // Hapus sub-layanan
    public function destroySubLayanan($id)
    {
        $subLayanan = SubLayanan::find($id);
        if (!$subLayanan) {
            return response()->json([
                'success' => false,
                'message' => 'Sub-layanan tidak ditemukan',
            ], 404);
        }

        $subLayanan->delete();

        return redirect()->route('terapis.layanan.index')->with('success', 'Sub layanan berhasil dihapus.');
    }
}
