<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Support\Geo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $sekolahs = Sekolah::query()
            ->when($request->q, function ($query, $q) {
                $query->where(function ($w) use ($q) {
                    $w->whereLike('nama', "%{$q}%")
                    ->orWhereLike('npsn', "%{$q}%")
                    ->orWhereLike('kecamatan', "%{$q}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sekolah.index', compact('sekolahs'));
    }

    public function create()
    {
        return view('admin.sekolah.create');
    }

    public function store(Request $request)
    {
        Sekolah::create($request->validate($this->rules()));

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit(Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        return view('admin.sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $data = $request->validate($this->rules($sekolah->id));

        if ($request->user()->isSekolah()) {
            // NPSN adalah identitas resmi: tidak boleh diubah oleh sekolah
            unset($data['npsn']);

            // Latihan 4: sekolah hanya boleh menggeser koordinat dalam jarak terbatas
            $jarak = Geo::jarakMeter(
                (float) $sekolah->latitude,
                (float) $sekolah->longitude,
                (float) $data['latitude'],
                (float) $data['longitude'],
            );
            $batas = (int) config('sig.maks_pindah_meter');

            if ($jarak > $batas) {
                $pesan = 'Koordinat baru berjarak ' . round($jarak) . ' m dari titik semula. '
                    . "Batas untuk akun sekolah adalah {$batas} m. "
                    . 'Hubungi Dinas Pendidikan jika lokasi sekolah memang berpindah.';

                return back()->withInput()
                    ->withErrors(['latitude' => $pesan])
                    ->with('error', $pesan);
            }
        }

        $sekolah->update($data);

        return $this->kembali($request)
            ->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        // Latihan 5: hapus berkas logo agar tidak tertinggal
        $sekolah->load('profil');
        if ($sekolah->profil?->logo_path) {
            Storage::disk('public')->delete($sekolah->profil->logo_path);
        }

        $sekolah->delete();

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }

    private function kembali(Request $request)
    {
        return $request->user()->isDinas()
            ? redirect()->route('dinas.sekolah.index')
            : redirect()->route('dashboard');
    }

    private function rules(?int $id = null): array
    {
        return [
            'npsn' => ['required', 'digits:8', Rule::unique('sekolahs', 'npsn')->ignore($id)],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(['SD', 'SMP', 'SMA', 'SMK'])],
            'status' => ['required', Rule::in(['Negeri', 'Swasta'])],
            'alamat' => ['required', 'string'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];
    }
}