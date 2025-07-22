@extends('template.master')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold;">Daftar Layanan & Sublayanan</h3>
        </div>

        <div class="card-body">
            <a href="{{ route('terapis.layanan.create') }}" class="btn btn-pink mb-3">+ Tambah Layanan</a>

            <div class="table-responsive">
                <table id="example1" class="table table-striped table-bordered table-hover" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th>No</th>
                            <th>Nama Layanan</th>
                            <th>Sub Layanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($layanans as $key => $layanan)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $layanan->nama }}</td>
                                <td>
                                    @if ($layanan->sublayanans->isEmpty())
                                        <span class="text-muted">-</span>
                                    @else
                                        <ul>
                                            @foreach ($layanan->sublayanans as $sub)
                                                <li>
                                                    {{ $sub->nama }} - Rp{{ number_format($sub->harga, 0, ',', '.') }}
                                                    <a href="#" class="text-success ml-2"
                                                        onclick="editSubLayanan({{ $sub->id }}, '{{ $sub->nama }}', {{ $sub->harga }})">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <a href="#" onclick="confirmDelete('sub{{ $sub->id }}')" class="text-danger ml-2"><i
                                                            class="fas fa-trash"></i></a>

                                                    <form id="deleteFormsub{{ $sub->id }}"
                                                        action="{{ route('terapis.layanan.sub.destroy', $sub->id) }}" method="POST"
                                                        style="display:none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('terapis.layanan.createSubLayanan', ['layanan_id' => $layanan->id]) }}"
                                        class="btn btn-sm btn-primary">
                                        + Sub
                                    </a>

                                    <a href="#" class="text-success ml-2"
                                        onclick="editLayanan({{ $layanan->id }}, '{{ $layanan->nama }}')">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <a href="#" onclick="confirmDelete('main{{ $layanan->id }}')" class="text-danger ml-2">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <form id="deleteFormmain{{ $layanan->id }}"
                                        action="{{ route('terapis.layanan.destroy', $layanan->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Edit SubLayanan -->
    <div class="modal fade" id="editSubModal" tabindex="-1" role="dialog" aria-labelledby="editSubModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editSubForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSubModalLabel">Edit Sub Layanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="subId">
                        <div class="form-group">
                            <label>Nama Sub Layanan</label>
                            <input type="text" class="form-control" id="subNama" name="nama" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control" id="subHarga" name="harga" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Layanan -->
    <div class="modal fade" id="editLayananModal" tabindex="-1" role="dialog" aria-labelledby="editLayananModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editLayananForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLayananModalLabel">Edit Layanan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="layananId">
                        <div class="form-group">
                            <label>Nama Layanan</label>
                            <input type="text" class="form-control" id="layananNama" name="nama" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-pink">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


@endsection

@section('js')
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>

        function editSubLayanan(id, nama, harga) {
            $('#editSubModal').modal('show');
            $('#subId').val(id);
            $('#subNama').val(nama);
            $('#subHarga').val(harga);
            $('#editSubForm').attr('action', '/terapis/layanan/sub/' + id);
        }

        function editLayanan(id, nama) {
            $('#editLayananModal').modal('show');
            $('#layananId').val(id);
            $('#layananNama').val(nama);
            $('#editLayananForm').attr('action', '/terapis/layanan/' + id);
        }



        $(function () {
            var table = $("#example1").DataTable({
                scrollX: true,
                autoWidth: false,
                responsive: true
            });

            table.buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        function confirmDelete(id) {
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
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }


    </script>
@endsection