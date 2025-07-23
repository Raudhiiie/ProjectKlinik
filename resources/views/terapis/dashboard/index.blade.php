@extends('template.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/css/dashboard-terapis.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">Dashboard Terapis</h3>

        <!-- Info Box: Dokter & Terapis -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="info-box">
                    <img src="https://cdn-icons-png.flaticon.com/512/9570/9570587.png" alt="Dokter">
                    <div>
                        <strong class="d-block">Dokter</strong>
                         1 orang
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-box">
                    <img src="https://cdn-icons-png.flaticon.com/512/10372/10372721.png" alt="Terapis">
                    <div>
                        <strong class="d-block">Terapis</strong>
                        {{ $jumlah_terapis }} orang
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Antrian Konsultasi -->
        <div class="card">
            <div class="card-header">
                <strong>Menunggu Konsultasi</strong>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Waktu Konsul</th>
                            <th>Antrian (Slot)</th>
                            <th>Pasien</th>
                            <th>Status Panggil</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($antrian as $data)
                            <tr>
                                <td>
                                    {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('j M Y') }}<br>
                                </td>
                                <td>
                                    {{ str_pad($data->no_antrian, 3, '0', STR_PAD_LEFT) }} <br>
                                    <span class="badge badge-secondary badge-status">{{ ucfirst($data->status) }}</span>
                                </td>
                                <td>
                                    {{ $data->pasien->nama ?? '-' }}<br>
                                    {{ $data->pasien->no_rm ?? '-' }}
                                </td>
                                <td>
                                    <span
                                        class="badge {{ $data->status_panggil === 'Dipanggil' ? 'badge-success' : 'badge-warning' }}">
                                        {{ $data->status_panggil }}
                                    </span>
                                </td>
                                <td>
                                    @if($data->status_panggil === 'Belum Dipanggil')
                                        <form method="POST" action="{{ route('terapis.antrian.panggil', $data->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">Panggil</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Sudah Dipanggil</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada antrian saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Alert Pasien Hari Ini -->
        <div class="alert alert-info">
            <strong>Pasien Hari Ini:</strong> {{ $hari_ini }} orang
        </div>

        <!-- Grafik Pasien -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <ul class="nav nav-tabs card-header-tabs" id="grafikTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="bulanan-tab" data-toggle="tab" href="#bulanan" role="tab">Bulanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="harian-tab" data-toggle="tab" href="#harian" role="tab">Hari Ini</a>
                    </li>
                </ul>
            </div>
            <div class="card-body tab-content">
                <div class="tab-pane fade show active" id="bulanan" role="tabpanel">
                    <canvas id="grafikBulanan" class="w-100" style="max-height: 250px;"></canvas>
                </div>
                <div class="tab-pane fade" id="harian" role="tabpanel">
                    <canvas id="grafikHarian" class="w-100" style="max-height: 250px;"></canvas>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxBulanan = document.getElementById('grafikBulanan').getContext('2d');
        new Chart(ctxBulanan, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels_bulan) !!},
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: {!! json_encode($data_bulanan) !!},
                    fill: false,
                    borderColor: '#4bc0c0',
                    backgroundColor: '#4bc0c0',
                    tension: 0.2
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
@endsection