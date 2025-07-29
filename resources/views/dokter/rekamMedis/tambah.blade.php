@extends('template/master')

@section('content')
<br>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Rekam Medis</h3>
        </div>
        <form action="{{ route('dokter.rekamMedis.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>No RM</label>
                        <select name="no_rm" id="no_rm" class="form-control select2" required>
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($pasien as $p)
                                <option value="{{ $p->no_rm }}">{{ $p->no_rm }} - {{ $p->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Tanggal</label>
                        <input type="date" class="form-control" name="tanggal" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Keluhan</label>
                        <textarea name="keluhan" class="form-control" rows="4" placeholder="Masukkan keluhan"></textarea>
                    </div>

                    <div class="col-md-6 form-group">
                        <label>Obat</label>
                        <input type="text" class="form-control" name="obat" placeholder="Masukkan Obat" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Terapis</label>
                        <select name="terapis_id" class="form-control select2" required>
                            <option value="">-- Pilih Terapis --</option>
                            @foreach ($terapis as $t)
                                <option value="{{ $t->id }}">{{ $t->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Sub Layanan --}}
                <div class="form-group mt-3">
                    <label>Sub Layanan (Tindakan)</label>
                    <div class="accordion" id="accordionExample">
                        @foreach ($layanan as $l)
                            <div class="card">
                                <div class="card-header" id="heading{{ $l->id }}">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse"
                                            data-target="#collapse{{ $l->id }}" aria-expanded="false"
                                            aria-controls="collapse{{ $l->id }}">
                                            {{ $l->nama }}
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapse{{ $l->id }}" class="collapse" aria-labelledby="heading{{ $l->id }}"
                                    data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($l->subLayanans as $sub)
                                                <div class="col-md-6">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="sub_layanan_ids[]"
                                                            value="{{ $sub->id }}" id="sub_{{ $sub->id }}">
                                                        <label class="form-check-label" for="sub_{{ $sub->id }}">
                                                            {{ $sub->nama }} - Rp{{ number_format($sub->harga) }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="card-footer text-right">
                <button type="submit" class="btn btn-pink">Tambah</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css">
@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>
    <script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>

    <script>
        $(function () {
            $('.select2').select2({
                placeholder: "Pilih",
                allowClear: true
            });
        });
    </script>
@endsection
