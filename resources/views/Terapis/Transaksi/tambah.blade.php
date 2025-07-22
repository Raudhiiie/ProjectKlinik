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
                                        @foreach($subLayanan as $item)
                                            <option value="{{ $item->id }}" data-harga="{{ $item->harga }}"
                                                data-jenis="layanan">
                                                {{ $item->nama }}
                                            </option>
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

    <select id="template-item-select" class="d-none">
        <option value="">Pilih item</option>
        <optgroup label="Layanan">
            @foreach($subLayanan as $l)
                <option value="{{ $l->id }}" data-harga="{{ $l->harga }}" data-jenis="layanan">
                    {{ $l->nama }} - Rp{{ number_format($l->harga, 0, ',', '.') }}
                </option>
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
        $(document).ready(function () {
            // Tambah item baru
            $('#btn-add-item').click(function () {
                const index = $('#items-table tbody tr').length;
                const newRow = `
    <tr>
        <td>
            <select class="form-control jenis-item" name="items[${index}][jenis]">
                <option value="layanan">Layanan</option>
                <option value="produk">Produk</option>
            </select>
        </td>
        <td>
            <select class="form-control item-select" name="items[${index}][id]" required>
                ${$('#template-item-select').html()}
            </select>
        </td>
        <td><input type="number" name="items[${index}][jumlah]" class="form-control jumlah" min="1" value="1" required></td>
        <td><input type="text" class="form-control harga" readonly></td>
        <td><input type="text" class="form-control subtotal" readonly></td>
        <td><button type="button" class="btn btn-danger btn-remove-item">Hapus</button></td>
    </tr>`;
                $('#items-table tbody').append(newRow);

                updateTotal();
            });

            // Hapus item
            $(document).on('click', '.btn-remove-item', function () {
                if ($('#items-table tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    updateTotal();
                }
            });

            // Update pilihan item berdasarkan jenis
            $(document).on('change', '.jenis-item', function () {
                const jenis = $(this).val();
                const itemSelect = $(this).closest('tr').find('.item-select');

                itemSelect.empty();
                if (jenis === 'layanan') {
                    @foreach($subLayanan as $item)
                        itemSelect.append(`<option value="{{ $item->id }}" data-harga="{{ $item->harga }}" data-jenis="layanan">{{ $item->nama }}</option>`);
                    @endforeach
                    } else {
                    @foreach($produk as $item)
                        itemSelect.append(`<option value="{{ $item->id }}" data-harga="{{ $item->harga }}" data-jenis="produk">{{ $item->nama_produk }}</option>`);
                    @endforeach
                    }

                updateRow($(this).closest('tr'));
            });

            // Update perhitungan
            $(document).on('change', '.item-select, .jumlah', function () {
                updateRow($(this).closest('tr'));
            });

            function updateRow(row) {
                const selected = row.find('.item-select option:selected');
                const harga = parseFloat(selected.data('harga')) || 0;
                const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
                const subtotal = harga * jumlah;

                row.find('.harga').val(harga.toLocaleString('id-ID'));
                row.find('.subtotal').val(subtotal.toLocaleString('id-ID'));

                updateTotal();
            }

            function updateTotal() {
                let total = 0;
                $('.subtotal').each(function () {
                    const value = $(this).val().replace(/\./g, '');
                    total += parseFloat(value) || 0;
                });

                $('#total-harga').val(total.toLocaleString('id-ID'));
            }

            // Inisialisasi pertama
            updateTotal();
        });
    </script>
@endsection