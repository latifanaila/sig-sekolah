<?php

namespace App\Models;

use App\Observers\AktivitasObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([AktivitasObserver::class])]
class ProfilSekolah extends Model
{
    protected $fillable = [
        'sekolah_id', 'kepala_sekolah', 'email', 'website', 'tahun_berdiri',
        'akreditasi', 'jumlah_siswa', 'jumlah_guru', 'deskripsi', 'logo_path',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    /** asset() mengikuti host yang dipakai (mis. 127.0.0.1:8000), bukan APP_URL. */
    public function logoUrl(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }
}