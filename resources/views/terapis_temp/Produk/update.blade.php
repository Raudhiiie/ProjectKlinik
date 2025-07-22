@extends('template/master')
@section('content')
    <br>
    <div class="col">
        <div class="card card-pink">
            <div class="card-header">
                <h3 class="card-title">Ubah Data Produk</h3>
            </div>
            <form action="{{ route('terapis.produk.update', ['posisi' => $posisi, 'id' => $produk->id]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk"
                                value="{{ $produk->nama_produk}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" id="tanggal" name="tanggal"
                                value="{{ $produk->tanggal}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>In</label>
                            <input type="number" class="form-control" id="in" name="in" oninput="calculateSisa()"
                                value="{{ $produk->in}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Out</label>
                            <input type="number" class="form-control" id="out" name="out" oninput="calculateSisa()"
                                value="{{ $produk->out}}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Sisa</label>
                            <input type="number" class="form-control" id="sisa" name="sisa" readonly
                                value="{{ $produk->sisa}}">
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
                                <option value="{{ $t->id }}"
                                    {{ old('terapis_id', $produk->terapis_id ?? '') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col col-md-12 form-group">
                            <label>Harga Produk</label>
                            <input type="number" class="form-control" id="harga" name="harga" value="{{ $produk->harga}}">
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-pink">Update</button>
                    </div>
            </form>
        </div>
    </div>

    <script>
        function calculateSisa() {
            var masuk = parseInt(document.getElementById('in').value);
            var out = parseInt(document.getElementById('out').value);
            var sisa = masuk - out;
            document.getElementById('sisa').value = sisa;
        }
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