<?php

namespace App\Http\Controllers;

use App\Models\Lists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = Lists::latest()->paginate(10);
        return view('list.index', compact('list'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('list.tambah');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'nopd' => 'required',
            'tglemail' => 'required',
            'uraian' => 'required',
            'rig' => 'required',
            'departement' => 'required',
            'jlh' => 'required',
            'realisasi' => 'required',
            'tglpembayaran' => 'required',
            'tglpelunasan' => 'required',
            'rekening' => 'required',
            'evidence' => 'required|file|mimes:pdf,jpg,png,jpeg|max:2048',
            'keterangan' => 'required',
        ]);
        $evidence = $request->file('evidence');
        $filename = $evidence->hashName();

        $evidence->storeAs('public/list', $filename);
        $list = Lists::create([

            'nopd' => $request->nopd,
            'tglemail' => $request->tglemail,
            'uraian' => $request->uraian,
            'rig' => $request->rig,
            'departement' => $request->departement,
            'jlh' => $request->jlh,
            'realisasi' => $request->realisasi,
            'selisih' => $request->selisih,
            'status' =>$request->status,
            'tglpembayaran' => $request->tglpembayaran,
            'tglpelunasan' => $request->tglpelunasan,
            'rekening' => $request->rekening,
            'evidence' => $filename,
            'keterangan' => $request->keterangan,
            
        ]);
        if ($list){
            return redirect()->route('list.index')->with(['success' => 'Data Berhasil Disimpan!']);
        }else{
            return redirect()->route('list.index')->with(['error' => 'Data Gagal Disimpan!']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Lists $lists)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $list = Lists::find($id);
        return view('list.update', compact('list'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'evidence' => 'nullable|file|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);
        $list = Lists::findOrFail($id);
        if($request->file('evidence') == ""){
            $list->update([
                'nopd' => $request->nopd,
                'tglemail' => $request->tglemail,
                'uraian' => $request->uraian,
                'rig' => $request->rig,
                'departement' => $request->departement,
                'jlh' => $request->jlh,
                'realisasi' => $request->realisasi,
                'tglpembayaran' => $request->tglpembayaran,
                'tglpelunasan' => $request->tglpelunasan,
                'rekening' => $request->rekening,
                'keterangan' => $request->keterangan,
            ]);
        }else{
            Storage::disk('local')->delete('public/list/' . $list->evidence);
            $evidence = $request->file('evidence');
            $evidence->storeAs('public/list', $evidence->hashName());
            $list->update([
                'nopd' => $request->nopd,
                'tglemail' => $request->tglemail,
                'uraian' => $request->uraian,
                'rig' => $request->rig,
                'departement' => $request->departement,
                'jlh' => $request->jlh,
                'realisasi' => $request->realisasi,
                'tglpembayaran' => $request->tglpembayaran,
                'tglpelunasan' => $request->tglpelunasan,
                'rekening' => $request->rekening,
                'evidence' => $evidence->hashName(),
                'keterangan' => $request->keterangan,
            ]);
        }
        if($list){
            return redirect()->route('list.index')->with(['success' => 'Data Berhasil Diubah!']);
        }else{
            return redirect()->route('list.index')->with(['error' => 'Data Gagal Diubah!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $list = Lists::findOrFail($id);
        Storage::disk('local')->delete('public/list/' . $list->evidence);
        $list->delete();
        if($list){
            return redirect()->route('list.index')->with(['success' => 'Data Berhasil Dihapus!']);
        }else{
            return redirect()->route('list.index')->with(['error' => 'Data Gagal Dihapus!']);
        }
    }
}
