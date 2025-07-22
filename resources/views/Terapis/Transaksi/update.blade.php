@extends('template/master')

@section('content')
<br>
<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Ubah Data Transaksi</h3>
        </div>
        <form action="{{ route('terapis.transaksi.update', ['transaksi' => $transaksi->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>No. RM Pasien</label>
                    <select name="no_rm" class="form-control" required>
                        <option value="">-- Pilih Pasien --</option>
                        @foreach($pasiens as $pasien)
                        <option value="{{ $pasien->no_rm }}" {{ $transaksi->no_rm == $pasien->no_rm ? 'selected' : '' }}>
                            {{ $pasien->no_rm }} - {{ $pasien->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Sub Layanan</label>
                    <select name="sub_layanan_id" class="form-control" required>
                        <option value="">-- Pilih Sub Layanan --</option>
                        @foreach($subLayanan as $layanan)
                        <option value="{{ $layanan->id }}" {{ $transaksi->sub_layanan_id == $layanan->id ? 'selected' : '' }}>
                            {{ $layanan->nama }} - Rp{{ number_format($layanan->harga, 0, ',', '.') }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $transaksi->tanggal }}" required>
                </div>

                <div class="form-group">
                    <label>Jenis</label>
                    <select name="jenis" class="form-control" required>
                        <option value="layanan" {{ $transaksi->jenis == 'layanan' ? 'selected' : '' }}>Layanan</option>
                        <option value="produk" {{ $transaksi->jenis == 'produk' ? 'selected' : '' }}>Produk</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <select name="metode_pembayaran" class="form-control" required>
                        <option value="cash" {{ $transaksi->metode_pembayaran == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="transfer" {{ $transaksi->metode_pembayaran == 'transfer' ? 'selected' : '' }}>Transfer</option>
                        <option value="qris" {{ $transaksi->metode_pembayaran == 'qris' ? 'selected' : '' }}>QRIS</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" class="form-control" min="1" value="{{ $transaksi->jumlah }}" required>
                </div>

                <div class="form-group">
                    <label>Keterangan (Opsional)</label>
                    <textarea name="keterangan" class="form-control">{{ $transaksi->keterangan }}</textarea>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-pink">Ubah</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
<link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('js')
<script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script>
    $(function () {
        $('#deskripsi_form').summernote();
    });
</script>
@endsection
