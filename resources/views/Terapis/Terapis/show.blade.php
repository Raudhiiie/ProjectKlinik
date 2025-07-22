@extends('template.master')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-white px-0">
                        <li class="breadcrumb-item"><a href="{{ route('terapis.terapis.index') }}">Data Terapis</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $terapis->nama }}</li>
                    </ol>
                </nav>

                <!-- Data Diri Pasien -->
                <div class="card">
                    <div class="card-header bg-pink text-white">
                        <h4 class="mb-0">Data Diri</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" value="{{ $terapis->nama }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" value="{{ $terapis->alamat }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">No HP</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" value="{{ $terapis->no_hp }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control"
                                    value="{{ \Carbon\Carbon::parse($terapis->tanggal_lahir)->format('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Bergabung</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control"
                                    value="{{ \Carbon\Carbon::parse($terapis->tanggal_bergabung)->format('d-m-Y') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jumlah Pasien</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control" value="{{ $pasienUnik }}">

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Data Tindakan -->
                <div class="card mt-4">
                    <div class="card-header bg-pink text-white">
                        <h4 class="mb-0">Data Tindakan</h4>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Tanggal</th>
                                    <th>Nama Pasien</th>
                                    <th>Jenis Tindakan</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tindakans as $tdk)
                                    <tr class="text-center">
                                        <td>{{ \Carbon\Carbon::parse($tdk->rekamMedis->tanggal)->format('d-m-Y') }}</td>
                                        <td>{{ $tdk->rekamMedis->no_rm }} - {{ $tdk->rekamMedis->pasien->nama ?? '-' }}</td>
                                        <td>{{ $tdk->sublayanan->layanan->nama ?? '-' }}</td>

                                        <td>1</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data tindakan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kembali -->
                <div class="mt-3">
                    <a href="{{ route('terapis.terapis.index') }}" class="btn btn-pink">← Kembali ke Daftar Terapis</a>
                </div>
            </div>
        </div>
    </div>
@endsection