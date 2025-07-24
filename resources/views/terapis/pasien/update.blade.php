@extends('template/master')

@section('content')
<br>
<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Ubah Data Pasien</h3>
        </div>

        <!-- Tampilkan error validasi -->
        @if ($errors->any())
            <div class="alert alert-danger m-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('terapis.pasien.update', ['pasien' => $pasien->no_rm]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $pasien->nama) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" value="{{ old('nik', $pasien->nik) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ old('alamat', $pasien->alamat) }}">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" value="{{ old('pekerjaan', $pasien->pekerjaan) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin">
                            <option value="" disabled {{ old('jenis_kelamin', $pasien->jenis_kelamin) == '' ? 'selected' : '' }}>Masukkan Jenis Kelamin</option>
                            <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>L</option>
                            <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>P</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pasien->tanggal_lahir) }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}">
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-pink">Update</button>
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
@endsection
