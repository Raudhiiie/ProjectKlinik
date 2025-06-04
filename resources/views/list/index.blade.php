@extends('template/master')

@section('css')
<!-- DataTables -->

<link rel="stylesheet" href="{{ url('https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ url('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
@if (session()->has('success'))
<div class="alert alert-primary">
    {{ session()->get('success') }}
</div>
@endif
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Data List PD</h3>
    </div>
    <div class="card-body">

        <div class="d-flex justify-content-between mb-3">
            <!-- "Tambah" button -->
            <a href="{{route('list.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> Tambah</a>


            <!-- DataTable buttons -->
            <div id="datatable-buttons"></div>
        </div>

        <!-- DataTable -->
        <div class="table-responsive">
            <table id="example1" class="table table-bordered display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">No PD</th>
                        <th style="text-align: center;">Tgl Email</th>
                        <th style="text-align: center;">Uraian</th>
                        <th style="text-align: center;">Rig</th>
                        <th style="text-align: center;">Departement</th>
                        <th style="text-align: center;">Jumlah</th>
                        <th style="text-align: center;">Realisasi</th>
                        <th style="text-align: center;">Selisih</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Tgl Pembayaran</th>
                        <th style="text-align: center;">Tgl Pelunasan</th>
                        <th style="text-align: center;">Rekening Penerima</th>
                        <th style="text-align: center;">Evidence Pembayaran</th>
                        <th style="text-align: center;">Keterangan</th>
                        <th style="text-align: center;">Aksi</th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $key => $data)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $data->nopd }}</td>
                        <td>{{ $data->tglemail }}</td>
                        <td>{{ $data->uraian }}</td>
                        <td>{{ $data->rig }}</td>
                        <td>{{ $data->departement }}</td>
                        <td>Rp {{ number_format($data->jlh, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($data->realisasi, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($data->selisih, 0, ',', '.') }}</td>

                        <td>
                            @if ($data->status === 'closed')
                            <span class="badge badge-danger">closed</span>
                            @elseif ($data->status === 'outstanding')
                            <span class="badge badge-warning">outstanding</span>
                            @endif
                        </td>
                        <td>{{ $data->tglpembayaran }}</td>
                        <td>{{ $data->tglpelunasan }}</td>
                        <td>{{ $data->rekening }}</td>
                        <td>
                            @if (pathinfo($data->evidence, PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{ Storage::url('list/' . $data->evidence) }}" target="_blank">
                                View PDF
                            </a>
                            @else
                            <a href="#" data-toggle="modal" data-target="#imageModal{{$key}}">
                                <img src="{{ Storage::url('list/' . $data->evidence) }}" style="width:150px" class="img-thumbnail">
                            </a>
                            @endif
                        </td>
                        <td>{{ $data->keterangan }}</td>
                        <td>
                            <!-- Edit -->
                            <a href="{{ route('list.edit', $data->id) }}" class="text-success" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- Delete -->
                            <a href="#" onclick="confirmDelete('{{ $data->id }}')" class="text-danger" title="Delete">
                                <i class="fas fa-trash"></i>
                            </a>

                            <form id="deleteForm{{ $data->id }}" action="{{ route('list.destroy', $data->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display: none;"></button>
                            </form>
                        </td>
                    </tr>
                    <!-- Modal -->
                    <div class="modal fade" id="imageModal{{$key}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <img src="{{ Storage::url('list/' . $data->evidence) }}" class="img-fluid">
                                </div>
                            </div>
                        </div>
                    </div>
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
<script src="{{ url('https://cdn.datatables.net/2.0.8/js/dataTables.js') }}"></script>
<script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.j')}}s"></script>
<script src="{{ url('plugins/datatables-buttons/js/dataTables.buttons.min.js')}}"></script>
<script src="{{ url('plugins/datatables-buttons/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{ url('plugins/jszip/jszip.min.js') }}"></script>
<script src="{{ url('plugins/pdfmake/pdfmake.min.js') }}"></script>
<script src="{{ url('plugins/pdfmake/vfs_fonts.js') }}"></script>
<script src="{{ url('plugins/datatables-buttons/js/buttons.html5.min.js')}}"></script>
<script src="{{ url('plugins/datatables-buttons/js/buttons.print.min.js')}}"></script>
<script src="{{ url('plugins/datatables-buttons/js/buttons.colVis.min.js')}}"></script>
<script src="{{url('https://cdn.jsdelivr.net/npm/sweetalert2@10')}}"></script>
<script src="{{url('https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js')}}"></script>

<!-- Page specific script -->
<script>
    $(function() {
        // Initialize DataTable
        var table = $("#example1").DataTable({
            "scrollX": true,
            "buttons": [{
                    extend: 'excel',
                    text: 'Excel',
                    exportOptions: {
                        columns: ':visible:not(.exclude-from-export)'
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c[r^="B"]', sheet).each(function() {
                            var url = $(this).text();
                            $(this).html('<a href="' + url + '">Download Evidence</a>');
                        });
                    }
                },
                "copy", 
                "csv",
                {
                    extend: 'pdf',
                    text: 'PDF',
                    exportOptions: { 
                        columns: ':visible', modifier: { page: 'all' } 
                    },
                    customize: function(doc) {
                        doc.defaultStyle.fontSize = 10; // Ukuran font lebih kecil
                        doc.styles.tableHeader.fontSize = 12; // Header lebih besar
                        doc.styles.tableHeader.bold = true;
                    }
                }
            ]
        });

        // Get the DataTable buttons container
        var buttonsContainer = table.buttons().container();

        // Move buttons container to a new location
        $('#datatable-buttons').append(buttonsContainer);
    });

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('deleteForm' + id);
                form.submit();
            }
        });
    }
</script>
@endsection