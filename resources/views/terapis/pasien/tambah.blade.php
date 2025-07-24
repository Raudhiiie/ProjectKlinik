@extends('template/master')
@section('content')
<br>
<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Tambah Data Pasien</h3>
        </div>
        <form action="{{ route('terapis.pasien.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>No RM</label>
                        <input type="text" class="form-control" id="no_rm" name="no_rm" placeholder="Masukkan No RM" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan Nama" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>NIK</label>
                        <input type="text" class="form-control" id="nik" name="nik" placeholder="Masukkan NIK" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" placeholder="Masukkan Alamat" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Pekerjaan</label>
                        <input type="text" class="form-control" id="pekerjaan" name="pekerjaan" placeholder="Masukkan Pekerjaan" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="" disabled selected>Masukkan Jenis Kelamin</option>
                            <option value="L">L</option>
                            <option value="P">P</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" placeholder="Masukkan No Hp" required>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-pink">Tambah</button>
            </div>
        </form>
    </div>
</div>

<script>
    function formatDate(inputId, outputId) {
        const input = document.getElementById(inputId);
        const output = document.getElementById(outputId);

        function updateFormattedDate() {
            const dateValue = input.value;
            if (dateValue) {
                const [year, month, day] = dateValue.split('-');
                const formattedDate = `${day}-${month}-${year}`;
                output.innerText = `Selected Date: ${formattedDate}`;
            } else {
                output.innerText = '';
            }
        }

        input.addEventListener('change', updateFormattedDate);
        updateFormattedDate();
    }

    formatDate('tanggal_lahir', 'formattedTanggalLahir');

    $(function() {
        $('#deskripsi_form').summernote();
    });
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
@endsection

@section('js')
<script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection
