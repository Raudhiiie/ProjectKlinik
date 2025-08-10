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
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; color: #000;">Data Terapis</h3>
        </div>
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <!-- "Tambah" button -->
                <a href="{{ route('terapis.terapis.create') }}" class="btn btn-pink"><i class="fa"></i>+ Terapis</a>

                <!-- DataTable buttons -->
                <div id="datatable-buttons"></div>
            </div>

            <!-- DataTable -->
            <div class="table-responsive">
                <table id="example1" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">Nama</th>
                            <th style="text-align: center;">Alamat</th>
                            <th style="text-align: center;">No HP</th>
                            <th style="text-align: center;">Tanggal Bergabung</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($terapis as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->alamat }}</td>
                                <td>{{ $data->no_hp }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_bergabung)->translatedFormat('j M Y') }}<br></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center" style="gap: 5px;">
                                        <!-- -- Detail -- -->
                                        <a href="{{ route('terapis.terapis.show', ['terapi' => $data->id]) }}"
                                            class="btn btn-sm btn-outline-primary" title="Lihat Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <!-- -- Edit -- -->
                                        <a href="{{ route('terapis.terapis.edit', ['terapi' => $data->id]) }}"
                                            class="btn btn-sm btn-outline-success" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- -- Delete -- -->
                                        <a href="#" onclick="confirmDelete('{{ $data->id}}')"
                                            class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <form id="deleteForm{{ $data->id }}"
                                            action="{{ route('terapis.terapis.destroy', ['terapi' => $data->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="display: none;"></button>
                                        </form>
                                    </div>
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
                scrollY: "400px",      // tinggi scroll vertikal
                scrollCollapse: true,  // tabel bisa mengecil jika datanya kurang
                autoWidth: false,
                responsive: true
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