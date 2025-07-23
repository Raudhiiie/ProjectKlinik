@extends('template.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h3>Tambah Produk ke Transaksi {{ $transaksi->id }}</h3>
    </div>
    <form method="POST" action="{{ route('terapis.transaksi.tambah.produk', $transaksi->id) }}">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Produk</label>
                <select name="produk_id" class="form-control" required>
                    @foreach($produk as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_produk }} - Rp{{ number_format($p->harga, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" class="form-control" min="1" required>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('terapis.transaksi.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
