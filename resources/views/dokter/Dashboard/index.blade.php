@extends('template.master')

@section('css')
    <link rel="stylesheet" href="{{ asset('dist/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('content')
    <div class="container-fluid dashboard-container">
        <h3 class="mb-4">Dashboard Terapis</h3>

        <!-- Grafik -->
        <div class="row justify-content-center mb-5">
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm">
                    <canvas id="chartHariIni"></canvas>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card p-3 shadow-sm">
                    <canvas id="chartBulanIni"></canvas>
                </div>
            </div>
        </div>

        <!-- Box Ringkasan -->
        <div class="row justify-content-center">
            <!-- Dua box di atas -->
            <div class="col-md-5 d-flex justify-content-center mb-4">
                <div class="info-box">
                    <i class="fas fa-user dashboard-icon"></i>
                    <div class="info-title">Pasien Hari Ini</div>
                    <div class="info-value">{{ $pasien_hari_ini }} orang</div>
                </div>
            </div>
            <div class="col-md-5 d-flex justify-content-center mb-4">
                <div class="info-box">
                    <i class="fas fa-users dashboard-icon"></i>
                    <div class="info-title">Pasien Bulan Ini</div>
                    <div class="info-value">{{ $pasien_bulan_ini }} orang</div>
                </div>
            </div>

            <!-- Satu box bawah -->
            <div class="col-md-12 d-flex justify-content-center">
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
        const chartHariIni = new Chart(document.getElementById('chartHariIni'), {
            type: 'bar',
            data: {
                labels: ['Hari Ini'],
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: [{{ $pasien_hari_ini }}],
                    backgroundColor: '#4e73df',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Grafik Pasien Hari Ini'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            callback: function (value) {
                                return Number.isInteger(value) ? value : '';
                            }
                        }
                    }
                }
            }
        });


        const chartBulanIni = new Chart(document.getElementById('chartBulanIni'), {
            type: 'bar',
            data: {
                labels: ['Bulan Ini'],
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: [{{ $pasien_bulan_ini }}],
                    backgroundColor: '#1cc88a',
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    title: {
                        display: true,
                        text: 'Grafik Pasien Bulan Ini'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function (value) {
                                return Number(value).toFixed(0);
                            }
                        }
                    }
                }
            }
        });

    </script>
@endsection