<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Monitor Antrian - Pretty's Clinic</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="{{ asset('dist/css/monitor.css') }}">

    </head>

    <body>

        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <img src="{{ asset('dist/img/logobulat.png') }}" alt="Logo" style="height: 50px;">
                <div class="clinic-name" style="font-size: 1.5rem; font-weight: bold;">Pretty’s Clinic</div>
            </div>
            <div class="clock" style="text-align: right;">
                <div class="time" id="clock" style="font-size: 1.2rem;">00:00</div>
                <div id="date">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>


        <div class="main">
            <div class="left-box">
                <div class="antrian-box">
                    <h2>ANTRIAN PASIEN</h2>
                    <div class="label-antrian">Nomor Antrian</div>
                    <div class="antrian-number">
                        {{ $antrianDipanggil ? str_pad($antrianDipanggil->no_antrian, 3, '0', STR_PAD_LEFT) : '---' }}
                    </div>
                </div>

                <div class="waiting-list">
                    @foreach ($antrianSelanjutnya as $item)
                        <div class="waiting-item">
                            {{ str_pad($item->no_antrian, 3, '0', STR_PAD_LEFT) }}
                            <small>Menunggu</small>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="right-box">
                <iframe src="https://www.youtube.com/embed/ikpHwSPawdY?autoplay=1&mute=1&loop=1&playlist=ikpHwSPawdY"
                    allow="autoplay; encrypted-media;" allowfullscreen></iframe>
            </div>
        </div>

        <div class="footer"></div>

        <script>
            function updateClock() {
                const now = new Date();
                const jam = String(now.getHours()).padStart(2, '0');
                const menit = String(now.getMinutes()).padStart(2, '0');
                document.getElementById('clock').innerText = `${jam}:${menit}`;
            }

            setInterval(updateClock, 1000);
            updateClock();
        </script>
    </body>

</html>