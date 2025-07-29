@extends('template/master')
@section('content')
    <br>
    <div class="col">
        <div class="card card-pink">
            <div class="card-header">
                <h3 class="card-title">Tambah Data Transaksi</h3>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('terapis.transaksi.store') }}" method="POST" onsubmit="return beforeSubmit()">


                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>Pasien</label>
                        <select name="no_rm" class="form-control" required>
                            @foreach($pasiens as $pasien)
                                <option value="{{ $pasien->no_rm }}">{{ $pasien->nama }} ({{ $pasien->no_rm }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="terapis_id">Terapis</label>
                        <select name="terapis_id" id="terapis_id" class="form-control" required>
                            <option value="">-- Pilih Terapis --</option>
                            @foreach ($terapis as $t)
                                <option value="{{ $t->id }}" {{ old('terapis_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('terapis_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <h5>Item Transaksi</h5>
                    <table class="table" id="items-table">
                        <thead>
                            <tr>
                                <th>Jenis</th>
                                <th>Item</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <select class="form-control jenis-item" name="items[0][jenis]">
                                        <option value="layanan">Layanan</option>
                                        <option value="produk">Produk</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control item-select" name="items[0][id]" required>
                                        @foreach($subLayananGroup as $layanan => $items)
                                            <optgroup label="{{ $layanan }}">
                                                @foreach($items as $item)
                                                    <option value="{{ $item->id }}" data-harga="{{ $item->harga }}"
                                                        data-jenis="layanan">
                                                        {{ $item->nama }} - Rp{{ number_format($item->harga, 0, ',', '.') }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </td>

                                <td><input type="number" name="items[0][jumlah]" class="form-control jumlah" min="1"
                                        value="1" required></td>
                                <td><input type="text" class="form-control harga" readonly></td>
                                <td><input type="text" class="form-control subtotal" readonly></td>
                                <td><button type="button" class="btn btn-danger btn-remove-item">Hapus</button></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="button" class="btn btn-primary" id="btn-add-item">Tambah Item</button>

                    <div class="form-group mt-3">
                        <label>Total Harga</label>
                        <input type="text" id="total-harga" class="form-control" readonly>
                    </div>
                    <!-- Tambahkan sebelum bagian card-footer -->
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-control" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="cash">Tunai</option>
                            <option value="transfer">Transfer</option>
                            <option value="qris">QRIS</option>
                            <option value="debit">Kartu Debit</option>
                        </select>
                        @error('metode_pembayaran')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <select name="keterangan" class="form-control" required>
                            <option value="Belum Bayar" selected>Belum Bayar</option>
                            <option value="Lunas">Lunas</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-pink">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Dropdown --}}
    <select id="template-item-select" class="d-none">
        <optgroup label="Layanan">
            @foreach($subLayananGroup as $layanan => $items)
                <optgroup label="{{ $layanan }}">
                    @foreach($items as $item)
                        <option value="{{ $item->id }}" data-harga="{{ $item->harga }}" data-jenis="layanan">
                            {{ $item->nama }} - Rp{{ number_format($item->harga, 0, ',', '.') }}
                        </option>
                    @endforeach
                </optgroup>
            @endforeach
        </optgroup>
        <optgroup label="Produk">
            @foreach($produk as $p)
                <option value="{{ $p->id }}" data-harga="{{ $p->harga }}" data-jenis="produk">
                    {{ $p->nama_produk }} - Rp{{ number_format($p->harga, 0, ',', '.') }}
                </option>
            @endforeach
        </optgroup>
    </select>

@endsection

@section('js')
    <script>
        function updateRow(row) {
            const selected = row.find('.item-select option:selected');
            const harga = parseFloat(selected.data('harga')) || 0;
            const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
            row.find('.harga').val(harga.toLocaleString('id-ID'));
            row.find('.subtotal').val((harga * jumlah).toLocaleString('id-ID'));
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            $('.subtotal').each(function () {
                const val = $(this).val().replace(/\./g, '');
                total += parseFloat(val) || 0;
            });
            $('#total-harga').val(total.toLocaleString('id-ID'));
        }

        $(document).ready(function () {
            $('#btn-add-item').click(function () {
                const index = $('#items-table tbody tr').length;
                const html = `
                    <tr>
                        <td>
                            <select class="form-control jenis-item" name="items[${index}][jenis]">
                                <option value="layanan">Layanan</option>
                                <option value="produk">Produk</option>
                            </select>
                        </td>
                        <td>
                            <select class="form-control item-select" name="items[${index}][id]">
                                ${$('#template-item-select').html()}
                            </select>
                        </td>
                        <td><input type="number" name="items[${index}][jumlah]" class="form-control jumlah" value="1" min="1"></td>
                        <td><input type="text" class="form-control harga" readonly></td>
                        <td><input type="text" class="form-control subtotal" readonly></td>
                        <td><button type="button" class="btn btn-danger btn-remove-item">Hapus</button></td>
                    </tr>`;
                $('#items-table tbody').append(html);
            });

            $(document).on('click', '.btn-remove-item', function () {
                if ($('#items-table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    updateTotal();
                }
            });

            $(document).on('change', '.item-select, .jumlah', function () {
                updateRow($(this).closest('tr'));
            });

            $(document).on('change', '.jenis-item', function () {
                const jenis = $(this).val();
                const row = $(this).closest('tr');
                const itemSelect = row.find('.item-select');
                itemSelect.empty();

                if (jenis === 'layanan') {
                    itemSelect.append($('#template-item-select optgroup[label!="Produk"]').html());
                } else {
                    itemSelect.append($('#template-item-select optgroup[label="Produk"]').html());
                }

                updateRow(row);
            });

            // Init
            updateRow($('#items-table tbody tr').first());
        });
    </script>
@endsection