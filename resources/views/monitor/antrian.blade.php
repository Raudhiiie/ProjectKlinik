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
                        @if ($antrianDipanggil)
                            {{ str_pad($antrianDipanggil->no_antrian, 3, '0', STR_PAD_LEFT) }}
                        @elseif($antrianTerakhirSelesai)
                            {{ str_pad($antrianTerakhirSelesai->no_antrian, 3, '0', STR_PAD_LEFT) }}
                        @else
                            ---
                        @endif
                    </div>
                    <div class="status-container">
                        @if ($antrianDipanggil)
                            @if ($antrianDipanggil->status === 'proses')
                                <div class="status-antrian status-konsultasi" style="font-size: 2rem;">Status: Konsultasi</div>
                            @elseif ($antrianDipanggil->status === 'selesai')
                                <div class="status-antrian status-selesai" style="font-size: 2rem;">Status: Selesai</div>
                            @else
                                <div class="status-antrian status-menunggu" style="font-size: 2rem;">Status: Menunggu</div>
                            @endif
                        @elseif($antrianTerakhirSelesai)
                            <div class="status-antrian status-selesai" style="font-size: 2rem;">Status: Selesai</div>
                        @else
                            <div class="status-antrian status-tidak-ada" style="font-size: 2rem;">Status: Tidak Ada Antrian
                            </div>
                        @endif
                    </div>
                </div>

                <div class="waiting-list">
                    @forelse ($antrianSelanjutnya as $item)
                        <div class="waiting-item">
                            {{ str_pad($item->no_antrian, 3, '0', STR_PAD_LEFT) }}
                            <small>Status: {{ ucfirst($item->status) }}</small>
                        </div>
                    @empty
                        <div class="waiting-item">Tidak ada antrian</div>
                    @endforelse
                </div>
            </div>

            <div class="right-box">
                <iframe src="https://www.youtube.com/embed/ikpHwSPawdY?autoplay=1&mute=1&loop=1&playlist=ikpHwSPawdY"
                    allow="autoplay; encrypted-media;" allowfullscreen></iframe>
            </div>
        </div>

        <div class="footer"></div>

        <script>
            // Update jam
            function updateClock() {
                const now = new Date();
                document.getElementById('clock').innerText =
                    now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
            setInterval(updateClock, 1000);
            updateClock();

            // Update data antrian + waiting list
            function updateAntrian() {
                fetch("{{ route('monitor.antrian.data') }}")
                    .then(response => response.json())
                    .then(data => {
                        // Nomor antrian utama
                        document.querySelector('.antrian-number').innerText =
                            data.no_antrian ? data.no_antrian : '---';

                        // Status utama
                        const statusEl = document.querySelector('.status-antrian');
                        statusEl.classList.remove('status-konsultasi', 'status-selesai', 'status-menunggu', 'status-tidak-ada');

                        let statusText = 'Tidak Ada Antrian';
                        let statusClass = 'status-tidak-ada';

                        if (data.status) {
                            switch (data.status.toLowerCase()) {
                                case 'proses':
                                    statusText = 'Konsultasi';
                                    statusClass = 'status-konsultasi';
                                    break;
                                case 'selesai':
                                    statusText = 'Selesai';
                                    statusClass = 'status-selesai';
                                    break;
                                case 'menunggu':
                                    statusText = 'Menunggu';
                                    statusClass = 'status-menunggu';
                                    break;
                            }
                        }

                        statusEl.innerText = "Status: " + statusText;
                        statusEl.classList.add(statusClass);

                        // Update waiting list
                        const waitingListEl = document.querySelector('.waiting-list');
                        waitingListEl.innerHTML = '';

                        if (data.waiting_list && data.waiting_list.length > 0) {
                            data.waiting_list.forEach(item => {
                                const div = document.createElement('div');
                                div.classList.add('waiting-item');
                                div.innerHTML = `
                        ${item.no_antrian}
                        <small>Status: ${item.status}</small>
                    `;
                                waitingListEl.appendChild(div);
                            });
                        } else {
                            const div = document.createElement('div');
                            div.classList.add('waiting-item');
                            div.innerText = 'Tidak ada antrian';
                            waitingListEl.appendChild(div);
                        }
                    })
                    .catch(err => console.error('Gagal update antrian:', err));
            }
            // Jalankan setiap 1 detik
            setInterval(updateAntrian, 1000);
            updateAntrian();
        </script>
    </body>

</html>