@extends('template.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="container-fluid dashboard-container">
        <h3 class="mb-3">Dashboard Dokter</h3>

        <!-- Grafik -->
        <div class="row justify-content-center">
            <!-- Grafik Harian -->
            <div class="col-md-6 mb-3">
                <div class="card p-3 shadow-sm">
                    <h5 class="text-center mb-2">Grafik Pasien 7 Hari Terakhir</h5>
                    <canvas id="chartHarian" height="180"></canvas>
                </div>
            </div>

            <!-- Grafik Bulanan -->
            <div class="col-md-6 mb-3">
                <div class="card p-3 shadow-sm">
                    <h5 class="text-center mb-2">Grafik Pasien Tahun Ini</h5>
                    <canvas id="chartBulanan" height="180"></canvas>
                </div>
            </div>
        </div>

        <!-- Ringkasan -->
        <div class="row justify-content-center">
            <!-- Pasien Hari Ini -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <div class="info-box">
                    <i class="fas fa-user dashboard-icon"></i>
                    <div class="info-title">Pasien Hari Ini</div>
                    <div class="info-value">{{ $pasien_hari_ini }} orang</div>
                </div>
            </div>

            <!-- Pasien Bulan Ini -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <div class="info-box">
                    <i class="fas fa-users dashboard-icon"></i>
                    <div class="info-title">Pasien Bulan Ini</div>
                    <div class="info-value">{{ $pasien_bulan_ini }} orang</div>
                </div>
            </div>

            <!-- Rekam Medis -->
            <div class="col-md-4 d-flex justify-content-center mb-3">
                <div class="info-box">
                    <i class="fas fa-notes-medical dashboard-icon"></i>
                    <div class="info-title">Rekam Medis</div>
                    <div class="info-value">{{ $rekam_medis }} unit</div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        // Grafik Harian (Line Chart)
        new Chart(document.getElementById('chartHarian'), {
            type: 'line',
            data: {
                labels: {!! json_encode($labels_hari_final) !!},
                datasets: [{
                    label: 'Pasien Hari Ini',
                    data: {!! json_encode($data_harian_final) !!},
                    fill: false,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    tension: 0.4,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                    tooltip: { mode: 'index', intersect: false }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Grafik Bulanan (Bar Chart)
        new Chart(document.getElementById('chartBulanan'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($labels_bulan) !!},
                datasets: [{
                    label: 'Pasien per Bulan',
                    data: {!! json_encode($data_bulanan) !!},
                    backgroundColor: 'rgba(28, 200, 138, 0.7)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: true },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
@endsection
