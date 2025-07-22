@extends('template/master')
@section('content')
    <br>
    <div class="col">
        <div class="card card-pink">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Produk</h3>
            </div>
            <form action="{{ route('terapis.produk.store', $posisi) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                placeholder="Masukkan Nama Produk" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                placeholder="Masukkan Tanggal" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>In</label>
                            <input type="number" class="form-control" id="in" name="in" placeholder="Masukkan In"
                                oninput="calculateSisa()" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Out</label>
                            <input type="number" class="form-control" id="out" name="out" placeholder="Masukkan Out"
                                oninput="calculateSisa()" required>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Sisa</label>
                            <input type="number" class="form-control" id="sisa" name="sisa" placeholder="Sisa" readonly>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Posisi</label>
                            <input type="text" class="form-control" name="posisi" value="{{ ucfirst($posisi) }}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="terapis_id">Nama Terapis</label>
                        <select name="terapis_id" class="form-control" required>
                            <option value="">-- Pilih Terapis --</option>
                            @foreach ($terapis as $t)
                                <option value="{{ $t->id }}" {{ old('terapis_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Harga Produk</label>
                            <input type="number" class="form-control" id="harga" name="harga"
                                placeholder="Masukkan Harga Produk" required>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-pink">Tambah</button>
                    </div>
            </form>
        </div>
    </div>

    <script>
        function calculateSisa() {
            var masuk = parseInt(document.getElementById('in').value) || 0;
            var keluar = parseInt(document.getElementById('out').value) || 0;
            var sisa = masuk - keluar;
            document.getElementById('sisa').value = sisa < 0 ? 0 : sisa;
        }

        formatDate('tanggal_lahir', 'formattedTanggalLahir');

        $(function () {
            $('#deskripsi_form').summernote();
        });
    </script>

@endsection
@section('css')
    <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('js')
    <script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection