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

        return response()->json([
            'success' => true,
            'data' => $layanans,
        ]);
    }

    // Simpan layanan utama
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:layanans,nama',
        ]);

        $layanan = Layanan::create(['nama' => $request->nama]);

        return response()->json([
            'success' => true,
            'message' => 'Layanan utama berhasil dibuat',
            'data' => $layanan,
        ], 201);
    }

    // Tambah sub-layanan ke layanan utama
    public function storeSubLayanan(Request $request, $layananId)
    {
        $request->validate([
            'nama' => 'required|string',
            'harga' => 'required|integer|min:0',
        ]);

        $layanan = Layanan::find($layananId);
        if (!$layanan) {
            return response()->json(['success' => false, 'message' => 'Layanan tidak ditemukan'], 404);
        }

        $subLayanan = SubLayanan::create([
            'layanan_id' => $layanan->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sub-layanan berhasil dibuat',
            'data' => $subLayanan,
        ], 201);
    }

    // Update layanan utama
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|unique:layanans,nama,' . $id,
        ]);

        $layanan = Layanan::find($id);
        if (!$layanan) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan tidak ditemukan',
            ], 404);
        }

        $layanan->nama = $request->nama;
        $layanan->save();

        return response()->json([
            'success' => true,
            'message' => 'Layanan utama berhasil diperbarui',
            'data' => $layanan,
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Layanan utama berhasil dihapus',
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Sub-layanan berhasil diperbarui',
            'data' => $subLayanan,
        ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Sub-layanan berhasil dihapus',
        ]);
    }
}
