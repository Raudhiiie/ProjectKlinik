@extends('template.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/css/dashboard-terapis.css') }}">
@endsection

@section('content')
    <div class="container-fluid">
        <h3 class="mb-4">Dashboard Terapis</h3>

        <!-- Info Box: Dokter & Terapis -->
        <div class="row mb-4">
            <div class="col-md-6 col-12">
                <div class="info-box">
                    <img src="https://cdn-icons-png.flaticon.com/512/9570/9570587.png" alt="Dokter">
                    <div>
                        <strong class="d-block">Dokter</strong>
                        1 orang
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-12">
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
                                    @if($data->status_panggil === 'Belum Dipanggil' && $data->status === 'menunggu')
                                        <form method="POST" action="{{ route('terapis.antrian.panggil', $data->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary mb-1">Panggil</button>
                                        </form>
                                    @elseif($data->status_panggil === 'Dipanggil' && $data->status === 'proses')
                                        <form method="POST" action="{{ route('terapis.antrian.selesai', $data->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-success">Selesai</button>
                                        </form>
                                    @else
                                        <span class="text-muted">Sudah Selesai</span>
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
        <!-- Grafik Pasien -->
        <div class="row justify-content-center mb-5">
            <!-- Grafik Harian -->
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="text-center mb-3">Grafik Pasien 7 Hari Terakhir</h5>
                    <canvas id="chartHarian" height="200"></canvas>
                </div>
            </div>

            <!-- Grafik Bulanan -->
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="text-center mb-3">Grafik Pasien Tahun Ini</h5>
                    <canvas id="chartBulanan" height="200"></canvas>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('js')
    <!-- Load Chart.js dulu -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Grafik Bulanan
        // Grafik Harian (7 Hari Terakhir)
        var ctxHarian = document.getElementById('chartHarian').getContext('2d');
        var chartHarian = new Chart(ctxHarian, {
            type: 'line', // Tipe grafik, bisa diganti sesuai keinginan (line, bar, pie, dll)
            data: {
                labels: {!! json_encode($labels_hari) !!},
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: {!! json_encode($data_harian) !!}, // Data jumlah pasien
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Grafik Bulanan (Tahun Ini)
        var ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
        var chartBulanan = new Chart(ctxBulanan, {
            type: 'bar', // Tipe grafik, bisa diganti sesuai keinginan
            data: {
                labels: {!! json_encode($labels_bulan) !!}, // Bulan dalam setahun
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: {!! json_encode($data_bulanan) !!}, // Data jumlah pasien per bulan
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection