@extends('template/master')
@section('content')
<br>
<div class="col">
    <div class="card card-pink">
        <div class="card-header">
            <h3 class="card-title">Ubah Data Terapis</h3>
        </div>
        <form action="{{ route('terapis.terapis.update', ['terapi' => $terapis->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="{{ $terapis->nama}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Alamat</label>
                        <input type="text" class="form-control" id="alamat" name="alamat" value="{{ $terapis->alamat}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" value="{{ $terapis->no_hp}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="{{ $terapis->tanggal_lahir}}">
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Tanggal Bergabung</label>
                        <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung" value="{{ $terapis->tanggal_bergabung}}">
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-pink">Update</button>
                </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        $('#deskripsi_form').summernote();
    });

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
</script>

@endsection
@section('css')
<link rel="stylesheet" href="{{ url('plugins/summernote/summernote-bs4.min.css') }}">
@endsection
@section('js')
<script src="{{ url('plugins/summernote/summernote-bs4.min.js') }}"></script>
@endsection