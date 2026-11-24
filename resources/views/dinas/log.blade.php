@extends('layouts.app')
@section('title', 'Log Aktivitas')

@section('content')
    @php
        $fmt = fn ($v) => (is_scalar($v) && $v !== '') ? \Illuminate\Support\Str::limit((string) $v, 60) : '-';
        $warna = ['dibuat' => 'bg-green-100 text-green-800', 'diubah' => 'bg-yellow-100 text-yellow-800', 'dihapus' => 'bg-red-100 text-red-800'];
    @endphp

    <h1 class="text-2xl font-bold mb-4">Log Aktivitas</h1>

    <form method="GET" class="mb-4 flex gap-2">
        <select name="aksi" class="border rounded px-3 py-2">
            <option value="">Semua aksi</option>
            @foreach (['dibuat', 'diubah', 'dihapus'] as $a)
                <option value="{{ $a }}" @selected(request('aksi') === $a)>{{ ucfirst($a) }}</option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-gray-700 text-white rounded">Filter</button>
    </form>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-3">Waktu</th>
                    <th class="p-3">Pengguna</th>
                    <th class="p-3">Aksi</th>
                    <th class="p-3">Sekolah</th>
                    <th class="p-3">Perubahan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="border-t align-top">
                        <td class="p-3 whitespace-nowrap">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                        <td class="p-3">{{ $log->user_nama }}</td>
                        <td class="p-3">
                            <span class="px-2 py-1 rounded text-xs {{ $warna[$log->aksi] ?? 'bg-gray-100' }}">
                                {{ ucfirst($log->aksi) }} {{ $log->objek }}
                            </span>
                        </td>
                        <td class="p-3">{{ $log->sekolah_nama ?? '-' }}</td>
                        <td class="p-3">
                            @if ($log->perubahan)
                                <ul class="list-disc ml-4">
                                    @foreach ($log->perubahan as $kolom => $nilai)
                                        <li>
                                            <span class="font-medium">{{ $kolom }}</span>:
                                            {{ $fmt($nilai['lama'] ?? null) }} &rarr; {{ $fmt($nilai['baru'] ?? null) }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center">Belum ada aktivitas tercatat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $logs->links() }}</div>
@endsection