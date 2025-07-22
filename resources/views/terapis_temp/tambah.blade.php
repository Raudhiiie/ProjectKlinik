@extends('template/master')
@section('content')
<br>
<div class="col">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tambah Data PD</h3>
        </div>
        <form action="{{ route('list.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>No PD</label>
                        <input type="text" class="form-control" id="nopd" name="nopd" placeholder="Masukkan No PD" required>

                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Tanggal Email</label>
                        <input type="date" class="form-control" id="tglemail" name="tglemail" placeholder="Masukkan Tanggal Email" required>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Uraian</label>
                        <input type="text" class="form-control" id="uraian" name="uraian" placeholder="Masukkan Uraian" required>

                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label for="rig">Rig</label>
                        <select class="form-control" id="rig" name="rig" required>
                            <option value="" disabled selected>Masukkan Rig</option>
                            <option value="ARJ-01">ARJ-01</option>
                            <option value="ARJ-02">ARJ-02</option>
                            <option value="ARJ-03">ARJ-03</option>
                            <option value="ARJ-05">ARJ-05</option>
                            <option value="ARJ-06">ARJ-06</option>
                            <option value="ARJ-07">ARJ-07</option>
                            <option value="ARJ-08">ARJ-08</option>
                            <option value="ARJ-09">ARJ-09</option>
                            <option value="ARJ-10">ARJ-10</option>
                            <option value="ARJ-11">ARJ-11</option>
                            <option value="ARJ-12">ARJ-12</option>
                            <option value="ARJ-15">ARJ-15</option>
                            <option value="ARJ-16">ARJ-16</option>
                            <option value="ARJ-17">ARJ-17</option>
                            <option value="ARJ-21">ARJ-21</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label for="departemnet">Departement</label>
                        <select class="form-control" id="departement" name="departement" required>
                            <option value="" disabled selected>Masukkan Departement</option>
                            <option value="AM Office">AM Office</option>
                            <option value="DM Office">DM Office</option>
                            <option value="HSE Office">HSE Office</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Jumlah</label>
                        <input type="number" class="form-control" id="jlh" name="jlh" placeholder="Masukkan Jumlah" oninput="calculateSelisih()" required>

                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Realisasi</label>
                        <input type="number" class="form-control" id="realisasi" name="realisasi" placeholder="Masukkan Realisasi" oninput="calculateSelisih()" required>

                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Selisih</label>
                        <input type="number" class="form-control" id="selisih" name="selisih" placeholder="Selisih" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Status</label>
                        <input type="text" class="form-control" id="status" name="status"  placeholder="Status" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Tanggal Pembayaran</label>
                        <input type="date" class="form-control" id="tglpembayaran" name="tglpembayaran" placeholder="Masukkan Tanggal Pembayaran" required>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Tanggal Pelunasan</label>
                        <input type="date" class="form-control" id="tglpelunasan" name="tglpelunasan" placeholder="Masukkan Tanggal Pelunasan" required>
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Rekening</label>
                        <input type="text" class="form-control" id="rekening" name="rekening" placeholder="Masukkan Rekening" required>

                    </div>
                </div>
                <div class="row">
                    <div class="co col-md-12 form-group">
                        <label for="evidence">Evidence</label>
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="evidence" name="evidence" required>
                                <label class="custom-file-label" for="evidence">Pilih Evidence</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-12 form-group">
                        <label>Keterangan</label>
                        <input type="text" class="form-control" id="keterangan" name="keterangan" placeholder="Masukkan Keterangan" required>

                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Tambah</button>
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

        formatDate('tglemail', 'formattedTanggalEmail');
        formatDate('tglpembayaran', 'formattedTanggalPembayaran');
        formatDate('tglpelunasan', 'formattedTanggalPelunasan');

    function calculateSelisih() {
        var jumlah = parseInt(document.getElementById('jlh').value);
        var realisasi = parseInt(document.getElementById('realisasi').value);
        var selisih = jumlah - realisasi;
        document.getElementById('selisih').value = selisih;

        var status = selisih !== 0 ? 'outstanding' : 'closed';
        document.getElementById('status').value = status;

        var statusIndicator = document.getElementById('status-indicator');
        statusIndicator.textContent = status === 'outstanding' ? 'Outstanding' : 'Closed';
        statusIndicator.className = status === 'outstanding' ? 'text-warning mr-1 status-outstanding' : 'text-warning mr-1 status-closed';
    }

    // Apply formatRupiah function to input fields
    document.getElementById('jlh').addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp ');
    });

    document.getElementById('realisasi').addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp ');
    });

    document.getElementById('selisih').addEventListener('keyup', function(e) {
        this.value = formatRupiah(this.value, 'Rp ');
    });

    
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