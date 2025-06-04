<?php

namespace App\Http\Controllers;


use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'List Data Produk',
            'data' => $produk
        ]);

        //     $pasien = Pasien::latest()->paginate(10);
        //     return view('pasien.index', compact('pasien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produk.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'nama_produk' => 'required',
            'tanggal' => 'required',
            'in' => 'required|integer|min:0',
            'out' => 'required|integer|min:0',
            'posisi' => 'required',
        ]);

        $sisa = $request->in - $request->out;

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'tanggal' => $request->tanggal,
            'in' => $request->in,
            'out' => $request->out,
            'sisa' => $sisa,
            'posisi' => $request->posisi,
        ]);

        if ($produk) {
            return response()->json([
                'success' => true,
                'message' => 'Data Berhasil Disimpan!',
                'data' => $produk
            ], 201);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data Gagal Disimpan!',
            ], 500);
        }
        // $request->validate([
        //     'no_rm' => 'required',
        //     'nama' => 'required',
        //     'nik' => 'required',
        //     'alamat' => 'required',
        //     'pekerjaan' => 'required',
        //     'jenis_kelamin' => 'required',
        //     'tanggal_lahir' => 'required',
        //     'no_hp' => 'required',
        // ]);

        // $pasien = Pasien::create([

        //     'no_rm' => $request->no_rm,
        //     'nama' => $request->nama,
        //     'nik' => $request->nik,
        //     'alamat' => $request->alamat,
        //     'pekerjaan' => $request->pekerjaan,
        //     'jenis_kelamin' => $request->jenis_kelamin,
        //     'tanggal_lahir' => $request->tanggal_lahir,
        //     'no_hp' => $request->no_hp,

        // ]);
        // if ($pasien) {
        //     return redirect()->route('pasien.index')->with(['success' => 'Data Berhasil Disimpan!']);
        // } else {
        //     return redirect()->route('pasien.index')->with(['error' => 'Data Gagal Disimpan!']);
        // }
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
    public function edit($id)
    {
        $produk = Produk::find($id);
        return view('produk.update', compact('produk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        if (!$produk) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama_produk' => 'required',
            'tanggal' => 'required',
            'in' => 'required|integer|min:0',
            'out' => 'required|integer|min:0',
            'posisi' => 'required',
        ]);

        $sisa = $request->in - $request->out;

        $updated = $produk->update([
            'nama_produk' => $request->nama_produk,
            'tanggal' => $request->tanggal,
            'in' => $request->in,
            'out' => $request->out,
            'sisa' => $sisa,
            'posisi' => $request->posisi,
        ]);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Data produk berhasil diperbarui',
                'data' => $produk,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data produk',
            ]);
        }

        // $pasien = Pasien::findOrFail($no_rm);

        // $pasien->update([
        //     'nama' => $request->nama,
        //     'nik' => $request->nik,
        //     'alamat' => $request->alamat,
        //     'pekerjaan' => $request->pekerjaan,
        //     'jenis_kelamin' => $request->jenis_kelamin,
        //     'tanggal_lahir' => $request->tanggal_lahir,
        //     'no_hp' => $request->no_hp,
        // ]);

        // if ($pasien) {
        //     return redirect()->route('pasien.index')->with(['success' => 'Data Berhasil Diubah!']);
        // } else {
        //     return redirect()->route('pasien.index')->with(['error' => 'Data Gagal Diubah!']);
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk ->delete();
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
