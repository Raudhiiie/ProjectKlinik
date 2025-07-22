<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PasienController extends Controller
{

    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $pasien = Pasien::withCount('antrians')->latest()->paginate(10);

        return view('terapis.pasien.index', compact('pasien'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('terapis.pasien.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'no_rm' => 'required',
            'nama' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required',
            'no_hp' => 'required',
        ]);

        $pasien = Pasien::create([
            'no_rm' => $request->no_rm,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
        ]);

        if ($pasien) {
            return redirect()->route('terapis.pasien.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('terapis.pasien.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($no_rm)
    {
        $pasien = Pasien::with(['rekamMedis', 'antrians'])->where('no_rm', $no_rm)->firstOrFail();

        return view('terapis.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($no_rm)
    {
        $pasien = Pasien::find($no_rm);
        return view('terapis.pasien.update', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'nama' => 'required',
            'nik' => 'required',
            'alamat' => 'required',
            'pekerjaan' => 'required',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required',
        ]);

        $updated = $pasien->update([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'alamat' => $request->alamat,
            'pekerjaan' => $request->pekerjaan,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'no_hp' => $request->no_hp,
        ]);

        if ($updated) {
            return redirect()->route('terapis.pasien.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('terapis.pasien.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($no_rm)
    {
        $pasien = Pasien::where('no_rm', $no_rm)->first();

        if ($pasien) {
            $pasien->delete();
            return redirect()->route('terapis.pasien.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }
}
