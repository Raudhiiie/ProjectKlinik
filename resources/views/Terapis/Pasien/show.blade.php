@extends('template.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-white px-0">
                    <li class="breadcrumb-item"><a href="{{ route('terapis.pasien.index') }}">Data Pasien</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $pasien->nama }}</li>
                </ol>
            </nav>

            <!-- Data Diri Pasien -->
            <div class="card">
                <div class="card-header bg-pink text-white">
                    <h4 class="mb-0">Data Diri</h4>
                </div>
                <div class="card-body">
                    @php
                        $jumlahKunjungan = $pasien->antrians->count();
                    @endphp

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No RM</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->no_rm }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->nama }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->nik }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->jenis_kelamin }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->format('d-m-Y') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->alamat }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->pekerjaan }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">No HP</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $pasien->no_hp }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Jumlah Kunjungan</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control" value="{{ $jumlahKunjungan > 0 ? $jumlahKunjungan . ' kali' : 'Belum pernah' }}">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Riwayat Kunjungan -->
            <div class="card mt-4">
                <div class="card-header bg-pink text-white">
                    <h4 class="mb-0">Riwayat Kunjungan</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No Antrian</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Status Panggil</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien->antrians as $antri)
                            <tr class="text-center">
                                <td>{{ $antri->no_antrian }}</td>
                                <td>{{ \Carbon\Carbon::parse($antri->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ ucfirst($antri->status) }}</td>
                                <td>{{ $antri->status_panggil ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Belum ada kunjungan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Info Rekam Medis -->
            <div class="card mt-4">
                <div class="card-header bg-pink text-white">
                    <h4 class="mb-0">Info Rekam Medis</h4>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="text-center">
                                <th>No. RM</th>
                                <th>Tanggal</th>
                                <th>Keluhan</th>
                                <th>Tindakan</th>
                                <th>Obat</th>
                                <th>Terapis</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien->rekamMedis as $rm)
                            <tr class="text-center">
                                <td>{{ $rm->no_rm }}</td>
                                <td>{{ \Carbon\Carbon::parse($rm->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $rm->keluhan }}</td>
                                <td>{{ $rm->tindakan }}</td>
                                <td>{{ $rm->obat }}</td>
                                <td>{{ $rm->terapis->nama }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Belum ada data rekam medis.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Kembali -->
            <div class="mt-3">
                <a href="{{ route('terapis.pasien.index') }}" class="btn btn-pink">← Kembali ke Daftar Pasien</a>
            </div>
        </div>
    </div>
</div>
@endsection
