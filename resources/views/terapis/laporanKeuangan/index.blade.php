@extends('template.master')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm rounded p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="font-weight-bold">Laporan Keuangan</h3>
            <form action="{{ route('terapis.laporanKeuangan.index') }}" method="GET" class="d-flex gap-2 mb-4">
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ request('tanggal_selesai') }}">
                <button type="submit" class="btn btn-pink">Filter</button>
                <a href="{{ route('terapis.laporanKeuangan.index') }}" class="btn btn-outline-secondary">Reset</a>
            </form>


        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="bg-white border rounded shadow-sm p-3 text-center">
                    <h6 class="mb-1 font-weight-bold">Total Pendapatan</h6>
                    <div class="text-success font-weight-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                </div>
            </div>
            {{-- <div class="col-md-3">
                <div class="bg-white border rounded shadow-sm p-3 text-center">
                    <h6 class="mb-1 font-weight-bold">Total Pengeluaran</h6>
                    <div class="text-danger font-weight-bold">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
                </div>
            </div> --}}
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>TF</th>
                        <th>QRIS</th>
                        <th>Cash</th>
                        <th>Keluar Cash</th>
                        <th>Keluar TF</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transaksiPerTanggal as $row)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('j M Y') }}<br></td>
                        <td>Rp {{ number_format($row->tf, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->qris, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($row->cash, 0, ',', '.') }}</td>
                        <td>Rp 0</td> <!-- Dummy jika belum ada -->
                        <td>Rp 0</td>
                        <td>Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">Tidak ada data transaksi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection