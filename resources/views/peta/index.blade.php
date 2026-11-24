@extends('layouts.public')

@section('title', 'Peta Sekolah')

@section('content')
    <div class="toolbar">
        <label>Jenjang
            <select id="filter-jenjang">
                <option value="">Semua jenjang</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
            </select>
        </label>
        <label>Status
            <select id="filter-status">
                <option value="">Semua status</option>
                <option value="Negeri">Negeri</option>
                <option value="Swasta">Swasta</option>
            </select>
        </label>
        <label>
            <input type="checkbox" id="mode-area">
            Muat sesuai area peta
        </label>
        <button id="unduh" type="button">Unduh GeoJSON</button>
        <span id="jumlah"></span>
    </div>

    <div id="peta" class="peta"></div>
@endsection

@push('scripts')
<script>
    const map = Peta.buat('peta');
    const lapisan = L.layerGroup().addTo(map);

    let fiturSekarang = [];   // data yang sedang tampil (untuk diunduh)
    let urutan = 0;           // mencegah respons lama menimpa respons baru

    const elJenjang = document.getElementById('filter-jenjang');
    const elStatus = document.getElementById('filter-status');
    const elArea = document.getElementById('mode-area');
    const elJumlah = document.getElementById('jumlah');

    async function muat() {
        const saya = ++urutan;
        const area = elArea.checked;

        try {
            const fitur = await Peta.ambilSekolah(
                elJenjang.value,
                elStatus.value,
                area ? Peta.bbox(map) : ''
            );

            if (saya !== urutan) return; // sudah ada permintaan yang lebih baru

            fiturSekarang = fitur;
            lapisan.clearLayers();

            fitur.forEach((f) => {
                L.marker(Peta.latlng(f))
                    .bindPopup(Peta.popup(f))
                    .addTo(lapisan);
            });

            elJumlah.textContent = fitur.length + ' sekolah'
                + (area ? ' (dalam area peta)' : '');

            // Pada mode area, peta tidak boleh bergerak sendiri
            if (!area && fitur.length > 0) {
                map.fitBounds(L.latLngBounds(fitur.map(Peta.latlng)).pad(0.1));
            }
        } catch (e) {
            elJumlah.textContent = 'Gagal memuat data';
        }
    }

    elJenjang.addEventListener('change', muat);
    elStatus.addEventListener('change', muat);
    elArea.addEventListener('change', muat);

    // Latihan 8: bounding box. Muat ulang setelah peta selesai digeser/di-zoom.
    let tunda;
    map.on('moveend', () => {
        if (!elArea.checked) return;
        clearTimeout(tunda);
        tunda = setTimeout(muat, 300);
    });

    // Latihan 7: unduh data yang sedang tampil
    document.getElementById('unduh').addEventListener('click', () => {
        if (fiturSekarang.length === 0) {
            alert('Tidak ada data untuk diunduh.');
            return;
        }
        Peta.unduhGeoJSON(fiturSekarang, 'sekolah.geojson');
    });

    muat();
</script>
@endpush