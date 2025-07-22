@extends('template.master')

@section('content')
<div class="card">
    <div class="card-body">
        <h3>Tambah Sub Layanan untuk: <strong>{{ $layanan->nama }}</strong></h3>
        <form action="{{ route('terapis.sublayanan.store') }}" method="POST">
            @csrf
            <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">

            <div class="form-group">
                <label>Nama Sub Layanan</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('terapis.layanan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
