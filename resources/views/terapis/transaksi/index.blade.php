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
            <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; color: #000;">Data Transaksi</h3>
        </div>
        <div class="card-body">

            <div class="row mb-3 align-items-center justify-content-between">
                <div class="col-md-9 d-flex flex-wrap align-items-center gap-2">
                    <a href="{{ route('terapis.transaksi.create') }}" class="btn btn-pink mr-2 mb-2">
                        <i class="fa"></i> + Tambah Transaksi
                    </a>
                    <form action="{{ route('terapis.transaksi.index') }}" method="GET"
                        class="form-inline d-flex flex-wrap align-items-center">
                        <select name="status" class="form-control mr-2 mb-2">
                            <option value="">Semua Status</option>
                            <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Belum
                                Bayar</option>
                            <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        ...
                        <select name="nama_layanan" class="form-control mr-2 mb-2">
                            <option value="">-- Semua Transaksi --</option>
                            @foreach($allSubLayanan as $nama)
                                <option value="{{ $nama }}" {{ request('nama_layanan') == $nama ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>

                        <button type="submit" class="btn btn-pink mr-2 mb-2">Filter</button>
                        <a href="{{ route('terapis.transaksi.index') }}" class="btn btn-secondary mb-2">Reset</a>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table id="example1" class="table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pasien</th>
                            <th>Item Transaksi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Metode</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($transaksi as $t)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $t->pasien->nama ?? '-' }}</td>
                                <td>
                                    <ul class="mb-0 pl-3">
                                        @foreach ($t->details as $d)
                                            <li>
                                                @if(strtolower($d->jenis) == 'layanan')

                                                    {{ $d->subLayanan->layanan->nama ?? '-' }} - {{ $d->subLayanan->nama ?? '-' }}
                                                @else
                                                    {{ $d->produk->nama_produk ?? '-' }}
                                                @endif
                                                ({{ $d->jumlah }}x Rp{{ number_format($d->harga_satuan) }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>Rp{{ number_format($t->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if ($t->keterangan === 'Lunas')
                                        <span class="badge badge-success">Lunas</span>

                                        {{-- tampilkan tanggal bayar hanya jika ada --}}
                                        @if ($t->tanggal_bayar)
                                            <small class="text-muted d-block">
                                                {{ \Carbon\Carbon::parse($t->tanggal_bayar)->format('d/m/Y H:i') }}
                                            </small>
                                        @endif
                                    @else
                                        <span class="badge badge-warning">Belum Bayar</span>
                                    @endif
                                </td>

                                <td>{{ ucfirst($t->metode_pembayaran) }}</td>
                                <td>
                                    @if($t->keterangan == 'Belum Bayar')
                                        <button class="btn btn-sm btn-primary btn-bayar" data-id="{{ $t->id }}"
                                            data-total="{{ $t->total_harga }}">
                                            <i class="fas fa-money-bill-wave"></i> Bayar
                                        </button>
                                    @endif
                                    <a href="{{ route('terapis.transaksi.cetak', $t->id) }}" class="btn btn-sm btn-success"
                                        target="_blank">
                                        <i class="fas fa-print"></i>
                                    </a>
                                    <a href="{{ route('terapis.transaksi.tambah.produk', $t->id) }}"
                                        class="btn btn-sm btn-success">
                                        + Produk
                                    </a>
                                    <!-- -- Delete -- -->
                                    <a href="#" onclick="confirmDelete('{{ $t->id}}')" class="btn btn-sm btn-danger"
                                        title="Delete">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                    <form id="deleteForm{{ $t->id }}" action="{{ route('terapis.transaksi.destroy', $t->id) }}"
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
        </div>
    </div>


    <!-- Modal Pembayaran -->
    <div class="modal fade" id="modalBayar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formBayar" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="metode_pembayaran" class="form-control" required>
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer</option>
                                <option value="qris">QRIS</option>
                                <option value="debit">Kartu Debit</option>
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Total Tagihan</label>
                            <input type="text" id="totalTagihan" class="form-control" readonly>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Bayar</label>
                            <input type="number" name="jumlah_bayar" id="jumlahBayar" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Kembalian</label>
                            <input type="text" id="kembalian" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Pembayaran</button>
                    </div>
                </form>
            </div>
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
        $('#formBayar').on('submit', function (e) {
            const total = parseFloat($('#totalTagihan').data('total'));
            const bayar = parseFloat($('#jumlahBayar').val()) || 0;

            if (bayar < total) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Pembayaran Gagal',
                    text: 'Jumlah bayar kurang dari total tagihan!'
                });
            }
        });


        $(document).ready(function () {
            @if (session('error'))
                $('#modalBayar').modal('show');
            @endif

            // Handle tombol bayar
            $(document).on('click', '.btn-bayar', function () {
                const id = $(this).data('id');
                const total = $(this).data('total');

                $('#formBayar').attr('action', `/terapis/transaksi/${id}/bayar`);
                $('#totalTagihan').val('Rp' + formatRupiah(total));
                $('#totalTagihan').data('total', total);
                $('#jumlahBayar').val('');
                $('#kembalian').val('Rp0');

                $('#modalBayar').modal('show');
            });

            // Hitung kembalian
            $('#jumlahBayar').on('input', function () {
                const total = parseFloat($('#totalTagihan').data('total'));
                const bayar = parseFloat($(this).val()) || 0;
                const kembalian = bayar - total;
                $('#kembalian').val('Rp' + formatRupiah(kembalian));
            });

            function formatRupiah(angka) {
                return angka.toLocaleString('id-ID', { minimumFractionDigits: 0 });
            }

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