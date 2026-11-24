<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;

class SekolahPublikController extends Controller
{
    public function show(Sekolah $sekolah)
    {
        $sekolah->load('profil');

        return view('publik.sekolah', [
            'sekolah' => $sekolah,
            'profil' => $sekolah->profil,
        ]);
    }
}