<?php

namespace App\Http\Controllers;

use App\Models\Terapis;
use App\Models\Tindakan;
use App\Models\RekamMedisDetail;
use Illuminate\Http\Request;

class TerapisController extends Controller
{
    public function index()
    {
        $terapis = Terapis::latest()->paginate(10);
        return view('terapis.terapis.index', compact('terapis'));
    }

    public function show($id)
    {
        $terapis = Terapis::findOrFail($id);
        $tindakans = RekamMedisDetail::whereHas('rekamMedis', function ($query) use ($id) {
            $query->where('terapis_id', $id);
        })->with(['rekamMedis.pasien', 'sublayanan.layanan'])->get();

        // Ambil daftar pasien unik dari tindakan
        $pasienUnik = $tindakans->pluck('rekamMedis.pasien.no_rm')->unique()->count();

        return view('terapis.terapis.show', compact('terapis', 'tindakans', 'pasienUnik'));
    }

    public function create()
    {
        return view('terapis.terapis.tambah');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_bergabung' => 'required|date',
        ]);

        $terapis = Terapis::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_bergabung' => $request->tanggal_bergabung,
        ]);

        if ($terapis) {
            return redirect()->route('terapis.terapis.index')->with(['success' => 'Data Berhasil Disimpan!']);
        } else {
            return redirect()->route('terapis.terapis.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    public function edit($id)
    {
        $terapis = Terapis::find($id);
        return view('terapis.terapis.update', compact('terapis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $terapis = Terapis::findOrFail($id);

        $request->validate([
            'nama' => 'required',
            'alamat' => 'required',
            'no_hp' => 'required',
            'tanggal_lahir' => 'required|date',
            'tanggal_bergabung' => 'required|date',
        ]);

        $updated = $terapis->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_hp' => $request->no_hp,
            'tanggal_lahir' => $request->tanggal_lahir,
            'tanggal_bergabung' => $request->tanggal_bergabung,
        ]);

        if ($updated) {
            return redirect()->route('terapis.terapis.index')->with(['success' => 'Data Berhasil Diubah!']);
        } else {
            return redirect()->route('terapis.terapis.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    public function destroy($id)
    {
        $terapis = Terapis::findOrFail($id);
        if ($terapis) {
            $terapis->delete();
            return redirect()->route('terapis.terapis.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }
}
