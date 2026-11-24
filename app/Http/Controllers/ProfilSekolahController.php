<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfilSekolahController extends Controller
{
    public function edit(Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $profil = $sekolah->profil ?? new ProfilSekolah();

        return view('sekolah.profil', compact('sekolah', 'profil'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $data = $request->validate([
            'kepala_sekolah' => ['nullable', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'max:150'],
            'website' => ['nullable', 'url:http,https', 'max:255'],
            'tahun_berdiri' => ['nullable', 'integer', 'between:1800,' . date('Y')],
            'akreditasi' => ['nullable', Rule::in(['A', 'B', 'C', 'Belum Terakreditasi'])],
            'jumlah_siswa' => ['nullable', 'integer', 'min:0'],
            'jumlah_guru' => ['nullable', 'integer', 'min:0'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            // JPG, PNG, atau WEBP maksimal 1 MB (SVG ditolak karena bisa memuat skrip)
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:1024'],
            'hapus_logo' => ['nullable', 'boolean'],
        ]);

        $profilLama = $sekolah->profil;

        if ($request->hasFile('logo')) {
            if ($profilLama?->logo_path) {
                Storage::disk('public')->delete($profilLama->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('logos', 'public');
        } elseif ($request->boolean('hapus_logo') && $profilLama?->logo_path) {
            Storage::disk('public')->delete($profilLama->logo_path);
            $data['logo_path'] = null;
        }

        unset($data['logo'], $data['hapus_logo']);

        $sekolah->profil()->updateOrCreate([], $data);

        return back()->with('success', 'Profil sekolah berhasil disimpan.');
    }
}