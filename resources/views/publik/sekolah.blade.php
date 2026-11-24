@extends('layouts.app')
@section('title', $sekolah->nama)

@section('content')
    @php
        $web = $profil?->website;
        $webAman = $web && \Illuminate\Support\Str::startsWith($web, ['http://', 'https://']);
        $baris = [
            'NPSN' => $sekolah->npsn,
            'Jenjang' => $sekolah->jenjang . ' ' . $sekolah->status,
            'Alamat' => $sekolah->alamat . ', ' . $sekolah->kecamatan,
            'Telepon' => $sekolah->telepon,
            'Kepala sekolah' => $profil?->kepala_sekolah,
            'Akreditasi' => $profil?->akreditasi,
            'Tahun berdiri' => $profil?->tahun_berdiri,
            'Jumlah siswa' => $profil?->jumlah_siswa,
            'Jumlah guru' => $profil?->jumlah_guru,
        ];
    @endphp

    <a href="{{ url('/') }}" class="text-blue-700 text-sm">&larr; Kembali ke peta</a>

    <div class="bg-white rounded shadow p-6 mt-3 max-w-3xl">
        <div class="flex items-center gap-4 mb-4">
            @if ($profil?->logoUrl())
                <img src="{{ $profil->logoUrl() }}" alt="Logo {{ $sekolah->nama }}" class="h-16 w-16 object-contain">
            @endif
            <h1 class="text-2xl font-bold">{{ $sekolah->nama }}</h1>
        </div>

        <dl class="grid grid-cols-3 gap-y-2 text-sm">
            @foreach ($baris as $label => $nilai)
                <dt class="font-medium text-gray-600">{{ $label }}</dt>
                <dd class="col-span-2">{{ filled($nilai) ? $nilai : '-' }}</dd>
            @endforeach

            <dt class="font-medium text-gray-600">Website</dt>
            <dd class="col-span-2">
                @if ($webAman)
                    <a href="{{ $web }}" target="_blank" rel="noopener noreferrer" class="text-blue-700 underline">{{ $web }}</a>
                @else
                    -
                @endif
            </dd>
        </dl>

        @if (filled($profil?->deskripsi))
            <h2 class="font-bold mt-6 mb-1">Tentang sekolah</h2>
            <p class="text-sm">{!! nl2br(e($profil->deskripsi)) !!}</p>
        @endif

        <div id="peta-mini" class="w-full h-64 rounded mt-6"></div>
    </div>
@endsection

@push('scripts')
<script>
    const posisi = [{{ (float) $sekolah->latitude }}, {{ (float) $sekolah->longitude }}];
    const petaMini = L.map('peta-mini').setView(posisi, 16);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap'
    }).addTo(petaMini);
    L.marker(posisi).addTo(petaMini);
</script>
@endpush