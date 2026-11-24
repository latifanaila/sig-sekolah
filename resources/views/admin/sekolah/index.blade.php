@extends('layouts.app')
@section('title', 'Daftar Sekolah')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
        <h1 class="text-2xl font-bold">Daftar Sekolah</h1>
        <a href="{{ route('dinas.sekolah.create') }}"
           class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">+ Tambah Sekolah</a>
    </div>

    <form method="GET" class="mb-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NPSN..."
               class="w-full max-w-sm rounded border border-gray-300 px-3 py-2">
        <button class="rounded bg-gray-700 px-4 py-2 text-white">Cari</button>
    </form>

    <div class="overflow-x-auto rounded bg-white shadow">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="px-3 py-2">No</th>
                    <th class="px-3 py-2">NPSN</th>
                    <th class="px-3 py-2">Nama</th>
                    <th class="px-3 py-2">Jenjang</th>
                    <th class="px-3 py-2">Status</th>
                    <th class="px-3 py-2">Kecamatan</th>
                    <th class="px-3 py-2">Koordinat</th>
                    <th class="px-3 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sekolahs as $s)
                    <tr class="border-t">
                        <td class="px-3 py-2">{{ $sekolahs->firstItem() + $loop->index }}</td>
                        <td class="px-3 py-2">{{ $s->npsn }}</td>
                        <td class="px-3 py-2">{{ $s->nama }}</td>
                        <td class="px-3 py-2">{{ $s->jenjang }}</td>
                        <td class="px-3 py-2">{{ $s->status }}</td>
                        <td class="px-3 py-2">{{ $s->kecamatan }}</td>
                        <td class="px-3 py-2">{{ $s->latitude }}, {{ $s->longitude }}</td>
                        <td class="flex gap-2 px-3 py-2">
                            <a href="{{ route('sekolah.edit', $s) }}"
                               class="rounded bg-yellow-500 px-3 py-1 text-white">Edit</a>
                            <form method="POST" action="{{ route('dinas.sekolah.destroy', $s) }}"
                                  onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="rounded bg-red-600 px-3 py-1 text-white">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-3 py-4 text-center text-gray-500">Belum ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $sekolahs->links() }}</div>
@endsection