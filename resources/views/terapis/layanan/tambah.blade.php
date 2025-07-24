@extends('template.master')
@section('content')
<br>
<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Tambah Layanan & Sub Layanan</h3>
        </div>

        <form action="{{ route('terapis.layanan.store') }}" method="POST">
            @csrf
            <div class="card-body">
                {{-- Layanan Utama --}}
                <div class="form-group">
                    <label for="nama_layanan">Nama Layanan</label>
                    <input type="text" class="form-control" name="nama_layanan" id="nama_layanan" placeholder="Contoh: Terapi Fisik" required>
                </div>

                <hr>
                <h5>Sub Layanan</h5>

                <div id="subLayananContainer">
                    <div class="sub-layanan-group mb-3 border p-3 rounded">
                        <div class="form-group">
                            <label>Nama Sub Layanan</label>
                            <input type="text" name="sublayanans[0][nama]" class="form-control" placeholder="Contoh: Pemijatan" required>
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" name="sublayanans[0][harga]" class="form-control" placeholder="Contoh: 50000" required>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-sub-layanan d-none">Hapus</button>
                    </div>
                </div>

                <button type="button" class="btn btn-pink btn-sm" id="addSubLayanan">+ Tambah Sub Layanan</button>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-pink">Simpan</button>
                <a href="{{ route('terapis.layanan.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('js')
<script>
    let subLayananIndex = 1;

    document.getElementById('addSubLayanan').addEventListener('click', function () {
        const container = document.getElementById('subLayananContainer');

        const newGroup = document.createElement('div');
        newGroup.classList.add('sub-layanan-group', 'mb-3', 'border', 'p-3', 'rounded');

        newGroup.innerHTML = `
            <div class="form-group">
                <label>Nama Sub Layanan</label>
                <input type="text" name="sublayanans[${subLayananIndex}][nama]" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="sublayanans[${subLayananIndex}][harga]" class="form-control" required>
            </div>
            <button type="button" class="btn btn-danger btn-sm remove-sub-layanan">Hapus</button>
        `;

        container.appendChild(newGroup);
        subLayananIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-sub-layanan')) {
            e.target.closest('.sub-layanan-group').remove();
        }
    });
</script>
@endsection
