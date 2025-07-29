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
            <h3 class="card-title card-title-custom">Data Pasien</h3>
        </div>
        <div class="card-body">

            <div class="d-flex justify-content-between mb-3">
                <!-- "Tambah" button -->
                <a href="{{ route('terapis.pasien.create') }}" class="btn btn-pink"><i class="fa"></i>+ Pasien</a>

                <!-- DataTable buttons -->
                <div id="datatable-buttons"></div>
            </div>

            <!-- DataTable -->
            <div class="table-responsive">
                <table id="example1" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th style="text-align: center;">No</th>
                            <th style="text-align: center;">No RM</th>
                            <th style="text-align: center;">Nama</th>
                            <th style="text-align: center;">NIK</th>
                            <th style="text-align: center;">Alamat</th>
                            <th style="text-align: center;">Pekerjaan</th>
                            <th style="text-align: center;">Jenis Kelamin</th>
                            <th style="text-align: center;">Tanggal Lahir</th>
                            <th style="text-align: center;">No Hp</th>
                            <th style="text-align: center;">Jumlah Kunjungan</th>
                            <th style="text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pasien as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->no_rm }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->nik }}</td>
                                <td>{{ $data->alamat }}</td>
                                <td>{{ $data->pekerjaan }}</td>
                                <td>{{ $data->jenis_kelamin }}</td>
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_lahir)->format('d-m-Y') }}</td>
                                <td>{{ $data->no_hp }}</td>
                                <td class="text-center">
                                    {{ $data->antrians_count > 0 ? $data->antrians_count . ' kali' : 'Belum Pernah' }}
                                </td>

                                <td class="text-center">
                                    <!-- -- Detail -- -->
                                    <a href="{{ route('terapis.pasien.show', ['pasien' => $data->no_rm]) }}"
                                        class="text-primary" title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- -- Edit -- -->
                                    <a href="{{ route('terapis.pasien.edit', ['pasien' => $data->no_rm]) }}"
                                        class="text-success ml-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <!-- -- Delete -- -->
                                    <a href="#" onclick="confirmDelete('{{ $data->no_rm }}')" class="text-danger ml-2"
                                        title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <form id="deleteForm{{ $data->no_rm }}"
                                        action="{{ route('terapis.pasien.destroy', ['pasien' => $data->no_rm]) }}"
                                        method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="display: none;"></button>
                                    </form>
                                    <!-- -- Antrikan Pasien -- -->
                                    <form action="{{ route('terapis.antrian.store') }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="no_rm" value="{{ $data->no_rm }}">
                                        {{-- <input type="date" name="tanggal" required class="form-control form-control-sm d-inline" style="width: 150px;"> --}}
                                        <button type="submit" class="btn btn-sm btn-warning" title="Antrikan Pasien">
                                            <i class="">Antrikan</i>
                                        </button>
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