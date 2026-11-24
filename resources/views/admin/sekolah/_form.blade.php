@php
    $kelas = 'w-full rounded border border-gray-300 px-3 py-2';
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

<div class="grid gap-4 md:grid-cols-2">
    <div>
        <label class="mb-1 block font-medium">NPSN</label>
        <input type="text" name="npsn" maxlength="8" value="{{ old('npsn', $sekolah->npsn) }}"
               @readonly(auth()->user()->isSekolah()) class="{{ $kelas }}">
        @error('npsn') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Nama Sekolah</label>
        <input type="text" name="nama" value="{{ old('nama', $sekolah->nama) }}" class="{{ $kelas }}">
        @error('nama') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Jenjang</label>
        <select name="jenjang" class="{{ $kelas }}">
            <option value="">-- Pilih --</option>
            @foreach (['SD', 'SMP', 'SMA', 'SMK'] as $j)
                <option value="{{ $j }}" @selected(old('jenjang', $sekolah->jenjang) === $j)>{{ $j }}</option>
            @endforeach
        </select>
        @error('jenjang') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Status</label>
        <select name="status" class="{{ $kelas }}">
            <option value="">-- Pilih --</option>
            @foreach (['Negeri', 'Swasta'] as $st)
                <option value="{{ $st }}" @selected(old('status', $sekolah->status) === $st)>{{ $st }}</option>
            @endforeach
        </select>
        @error('status') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="mb-1 block font-medium">Alamat</label>
        <textarea name="alamat" rows="2" class="{{ $kelas }}">{{ old('alamat', $sekolah->alamat) }}</textarea>
        @error('alamat') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Kecamatan</label>
        <input type="text" name="kecamatan" value="{{ old('kecamatan', $sekolah->kecamatan) }}" class="{{ $kelas }}">
        @error('kecamatan') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Telepon (opsional)</label>
        <input type="text" name="telepon" value="{{ old('telepon', $sekolah->telepon) }}" class="{{ $kelas }}">
        @error('telepon') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Latitude</label>
        <input type="text" id="latitude" name="latitude" value="{{ old('latitude', $sekolah->latitude) }}" class="{{ $kelas }}">
        @error('latitude') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="mb-1 block font-medium">Longitude</label>
        <input type="text" id="longitude" name="longitude" value="{{ old('longitude', $sekolah->longitude) }}" class="{{ $kelas }}">
        @error('longitude') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-4">
    <div class="mb-2 flex items-center justify-between">
        <label class="font-medium">Klik peta atau geser marker untuk menentukan lokasi</label>
        <button type="button" id="btn-lokasi" class="rounded bg-green-600 px-3 py-1 text-sm text-white">
            Gunakan lokasi saya
        </button>
    </div>
    <div id="peta-form" style="height: 400px;" class="rounded border"></div>
</div>

<div class="mt-6 flex gap-2">
    <button type="submit" class="rounded bg-blue-600 px-5 py-2 text-white hover:bg-blue-700">Simpan</button>
    <a href="{{ auth()->user()->isDinas()
            ? route('dinas.sekolah.index')
            : route('dashboard') }}"
       class="rounded bg-gray-300 px-5 py-2">Batal</a>
</div>

@push('scripts')
    <script>
        const latInput = document.getElementById('latitude');
        const lngInput = document.getElementById('longitude');
        const pusat = [-3.3194, 114.5908]; // Banjarmasin

        const lat0 = parseFloat(latInput.value);
        const lng0 = parseFloat(lngInput.value);
        const ada = !isNaN(lat0) && !isNaN(lng0);

        const peta = L.map('peta-form').setView(ada ? [lat0, lng0] : pusat, ada ? 17 : 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(peta);

        const marker = L.marker(ada ? [lat0, lng0] : pusat, { draggable: true }).addTo(peta);

        function isiKolom(lat, lng) {
            latInput.value = lat.toFixed(7);
            lngInput.value = lng.toFixed(7);
        }

        peta.on('click', function (e) {
            marker.setLatLng(e.latlng);
            isiKolom(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function () {
            const p = marker.getLatLng();
            isiKolom(p.lat, p.lng);
        });

        function dariKolom() {
            const a = parseFloat(latInput.value);
            const b = parseFloat(lngInput.value);
            if (!isNaN(a) && !isNaN(b) && Math.abs(a) <= 90 && Math.abs(b) <= 180) {
                marker.setLatLng([a, b]);
                peta.panTo([a, b]);
            }
        }
        latInput.addEventListener('change', dariKolom);
        lngInput.addEventListener('change', dariKolom);

        // Latihan 4: tombol "Gunakan lokasi saya"
        document.getElementById('btn-lokasi').addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Browser Anda tidak mendukung geolokasi.');
                return;
            }
            navigator.geolocation.getCurrentPosition(function (pos) {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                marker.setLatLng([lat, lng]);
                peta.setView([lat, lng], 17);
                isiKolom(lat, lng);
            }, function () {
                alert('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.');
            });
        });
    </script>
@endpush