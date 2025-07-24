@extends('template.master')

@section('content')
<div class="container mt-4">
    <div class="card shadow rounded">
        <div class="card-header bg-pink text-white">
            <h4 class="mb-0">
                <i class="fas mr-2"></i>Tambah Sub Layanan
            </h4>
        </div>

        <div class="card-body">
            <h5 class="mb-4">Untuk Layanan: <strong>{{ $layanan->nama }}</strong></h5>

            <form action="{{ route('terapis.sublayanan.store') }}" method="POST">
                @csrf
                <input type="hidden" name="layanan_id" value="{{ $layanan->id }}">

                <div class="form-group">
                    <label for="nama">Nama Sub Layanan</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama sub layanan" required>
                </div>

                <div class="form-group">
                    <label for="harga">Harga</label>
                    <input type="number" name="harga" id="harga" class="form-control" placeholder="Masukkan harga" required>
                </div>

                <div class="text-right mt-4">
                    <button type="submit" class="btn btn-pink">
                        <i class="fas mr-1"></i> Simpan
                    </button>
                    <a href="{{ route('terapis.layanan.index') }}" class="btn btn-secondary">
                        <i class="fas mr-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
