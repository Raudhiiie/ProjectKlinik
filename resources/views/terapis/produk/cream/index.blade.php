@extends('template.master')

@section('css')
    <!-- DataTables -->

    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session()->get('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; color: #000;">Data Produk Cream</h3>
        </div>
        <div class="card-body">

            <div class="row mb-3 align-items-center justify-content-between">
                <!-- Kolom kiri: Tambah Produk + Filter (berdampingan) -->
                <div class="col-md-9 d-flex flex-wrap align-items-center gap-2">
                    <!-- Tombol Tambah -->
                    <a href="{{ route('terapis.produk.create', $posisi) }}" class="btn btn-pink mr-2 mb-2">
                        <i class="fa"></i> + Tambah Produk
                    </a>

                    <!-- Form Filter -->
                    <form action="{{ route('terapis.produk.index', $posisi) }}" method="GET"
                        class="form-inline d-flex flex-wrap align-items-center">
                        <select name="nama_produk" class="form-control mr-2 mb-2">
                            <option value="">-- Semua Produk --</option>
                    @php
                        $allProduk = \App\Models\Produk::where('posisi', $posisi)->pluck('nama_produk')->unique();
                    @endphp

                            @foreach($allProduk as $nama)
                                <option value="{{ $nama }}" {{ request('nama_produk') == $nama ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-pink mr-2 mb-2">Filter</button>
                        <a href="{{ route('terapis.produk.index', $posisi) }}" class="btn btn-secondary mb-2">Reset</a>
                    </form>
                </div>

                <!-- Kolom kanan: DataTable buttons -->
                <div class="col-md-3 text-md-right">
                    <div id="datatable-buttons"></div>
                </div>
            </div>



            <!-- DataTable -->
            <div class="table-responsive">
                <table id="example1" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Nama Produk</th>
                            <th style="text-align: center;">Tanggal</th>
                            <th style="text-align: center;">In</th>
                            <th style="text-align: center;">Out</th>
                            <th style="text-align: center;">Sisa</th>
                            <th style="text-align: center;">Posisi</th>
                            <th style="text-align: center;">Aksi</th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($produk as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nama_produk }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $data->in }}</td>
                                <td>{{ $data->out }}</td>
                                <td>{{ $data->sisa }}</td>
                                <td>{{ $data->posisi }}</td>
                                <td>
                                    <!-- Edit -->
                                    <a href="{{ route('terapis.produk.edit', ['posisi' => strtolower($data->posisi), 'id' => $data->id]) }}"
                                        class="text-success" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- Delete -->
                                    <a href="#" onclick="confirmDelete('{{ $data->id }}')" class="text-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ route('terapis.produk.destroy', ['posisi' => strtolower($data->posisi), 'id' => $data->id]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="display: none;"></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- End DataTable -->
        </div>
    </div>
@endsection
@section('js')
    <!-- DataTables & Plugins -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="{{asset('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.j')}}s"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

    <!-- Page specific script -->
    <script>
        $(function () {
            var table = $("#example1").DataTable({
                scrollX: true,
            });
            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(no_rm) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + no_rm).submit();
                }
            });
        }
    </script>
@endsection