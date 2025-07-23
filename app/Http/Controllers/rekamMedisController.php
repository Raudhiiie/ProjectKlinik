<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\RekamMedis;
use App\Models\Pasien;
use App\Models\Terapis;
use App\Models\Layanan;
use App\Models\RekamMedisDetail;
use App\Models\SubLayanan;
use App\Models\Transaksi;
use App\Models\TransaksiDetail;

class rekamMedisController extends Controller
{
    public function index()
    {
        // Ambil semua data rekam medis dan eager load data pasien (relasi)
        $rekamMedis = RekamMedis::with(['pasien', 'terapis', 'details.sublayanan.layanan'])->get();

        return view('dokter.rekamMedis.index', compact('rekamMedis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pasien = Pasien::all();
        $terapis = Terapis::all();
        $layanan = Layanan::with('subLayanans')->get();
        return view('dokter.rekamMedis.tambah', compact('pasien', 'terapis', 'layanan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm' => 'required',
            'terapis_id' => 'required',
            'tanggal' => 'required|date',
            'keluhan' => 'required|string',
            'obat' => 'required|string',
            'sub_layanan_ids' => 'required|array|min:1',
        ]);

        DB::beginTransaction();

        try {
            $rekamMedis = RekamMedis::create([
                'no_rm' => $request->no_rm,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan,
                'obat' => $request->obat,
                'terapis_id' => $request->terapis_id,
            ]);

            $tindakanText = [];


            foreach ($request->sub_layanan_ids as $id) {
                $sub = SubLayanan::with('layanan')->findOrFail($id);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'sublayanan_id' => $sub->id,
                    'harga' => $sub->harga,
                ]);

                $tindakanText[] = $sub->layanan->nama . ' - ' . $sub->nama;
            }


            $rekamMedis->update([
                'tindakan' => implode(', ', $tindakanText)
            ]);

            // Simpan transaksi utama
            $transaksi = Transaksi::create([
                'no_rm' => $request->no_rm,
                'terapis_id' => $request->terapis_id,
                'tanggal' => $request->tanggal,
                'metode_pembayaran' => $request->input('metode_pembayaran', 'cash'),
                'keterangan' => 'Belum Bayar',
                'total_harga' => 0,
            ]);
            $total = 0;

            // 2. Tambahkan layanan ke transaksi
            foreach ($request->sub_layanan_ids as $id) {
                $sub = SubLayanan::with('layanan')->findOrFail($id);

                TransaksiDetail::create([
                    'transaksi_id' => $transaksi->id,
                    'jenis' => 'layanan',
                    'sub_layanan_id' => $sub->id,
                    'jumlah' => 1,
                    'harga_satuan' => $sub->harga,
                    'subtotal' => $sub->harga,
                    'terapis_id' => $request->terapis_id,
                ]);

                $total += $sub->harga;
            }

            // 3. Update total transaksi
            $transaksi->update(['total_harga' => $total]);

            DB::commit();
            return redirect()->route('dokter.rekamMedis.index')->with('success', 'Data berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function show($no_rm)
    {
        $pasien = Pasien::with(['rekamMedis'])->where('no_rm', $no_rm)->firstOrFail();

        return view('dokter.rekamMedis.show', compact('pasien'));
    }

    public function edit(RekamMedis $rekamMedis)
    {
        $pasien = Pasien::all();
        $terapis = Terapis::all();
        $layanan = Layanan::with('subLayanans')->get();
        $selectedSubLayanan = $rekamMedis->details->pluck('sublayanan_id')->toArray();

        return view('dokter.rekamMedis.update', compact('rekamMedis', 'pasien', 'terapis', 'layanan', 'selectedSubLayanan'));
    }


    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $request->validate([
            'no_rm' => 'required|exists:pasiens,no_rm',
            'terapis_id' => 'required',
            'tanggal' => 'required|date',
            'keluhan' => 'required|string',
            'obat' => 'required|string',
            'sub_layanan_ids' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            $rekamMedis->update([
                'no_rm' => $request->no_rm,
                'terapis_id' => $request->terapis_id,
                'tanggal' => $request->tanggal,
                'keluhan' => $request->keluhan,
                'obat' => $request->obat,
            ]);

            RekamMedisDetail::where('rekam_medis_id', $rekamMedis->id)->delete();

            $tindakanText = [];

            foreach ($request->sub_layanan_ids as $id) {
                $sub = SubLayanan::with('layanan')->findOrFail($id);

                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'sublayanan_id' => $sub->id,
                    'harga' => $sub->harga ?? 0,
                ]);

                $tindakanText[] = $sub->layanan->nama . ' - ' . $sub->nama;
            }

            $rekamMedis->update([
                'tindakan' => implode(', ', $tindakanText),
            ]);

            // Simpan detail sublayanan
            $total = 0;
            foreach ($request->sublayanan_id as $key => $subId) {
                $harga = $request->harga[$key];
                RekamMedisDetail::create([
                    'rekam_medis_id' => $rekamMedis->id,
                    'sublayanan_id' => $subId,
                    'harga' => $harga,
                ]);
                $total += $harga;
            }

            // Simpan transaksi otomatis
            Transaksi::create([
                'rekam_medis_id' => $rekamMedis->id,
                'tanggal' => now(),
                'total_harga' => $total,
                'status' => 'belum bayar', // default
            ]);

            DB::commit();
            return redirect()->route('dokter.rekamMedis.index')->with('success', 'Data berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        $rekamMedis = RekamMedis::findOrFail($id);
        if ($rekamMedis) {
            $rekamMedis->delete();
            return redirect()->route('dokter.rekamMedis.index')->with('success', 'Data berhasil dihapus.');
        } else {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }
}
