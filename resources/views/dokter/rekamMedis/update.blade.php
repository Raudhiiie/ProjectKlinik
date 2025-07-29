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
                <h3 class="card-title">Edit Data Rekam Medis</h3>
            </div>
            <form action="{{ route('dokter.rekamMedis.update', $rekamMedis->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">

                    <div class="row">
                        {{-- No RM --}}
                        <div class="col-md-6 form-group">
                            <label>No RM</label>
                            <select name="no_rm" class="form-control select2" required>
                                <option value="">-- Pilih Pasien --</option>
                                @foreach($pasien as $p)
                                    <option value="{{ $p->no_rm }}" {{ $p->no_rm == $rekamMedis->no_rm ? 'selected' : '' }}>
                                        {{ $p->no_rm }} - {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal --}}
                        <div class="col-md-6 form-group">
                            <label>Tanggal</label>
                            <input type="date" class="form-control" name="tanggal" value="{{ $rekamMedis->tanggal }}"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Keluhan --}}
                        <div class="col-md-6 form-group">
                            <label>Keluhan</label>
                            <textarea class="form-control" name="keluhan" value="{{ $rekamMedis->keluhan }}"
                                required> </textarea>
                        </div>

                        {{-- Obat --}}
                        <div class="col-md-6 form-group">
                            <label>Obat</label>
                            <input type="text" class="form-control" name="obat" value="{{ $rekamMedis->obat }}" required>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Terapis --}}
                        <div class="col-md-6 form-group">
                            <label>Terapis</label>
                            <select name="terapis_id" class="form-control select2" required>
                                <option value="">-- Pilih Terapis --</option>
                                @foreach ($terapis as $t)
                                    <option value="{{ $t->id }}" {{ $t->id == $rekamMedis->terapis_id ? 'selected' : '' }}>
                                        {{ $t->nama }}
                                    </option>
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
                                                @if (!empty($l->subLayanans))
                                                    @foreach ($l->subLayanans as $sub)
                                                        <div class="col-md-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" name="sub_layanan_ids[]"
                                                                    value="{{ $sub->id }}" id="sub_{{ $sub->id }}"
                                                                    {{ in_array($sub->id, $selectedSubLayanan) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="sub_{{ $sub->id }}">
                                                                    {{ $sub->nama }} - Rp{{ number_format($sub->harga) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-pink">Perbarui</button>
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
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

    <!-- Summernote -->
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