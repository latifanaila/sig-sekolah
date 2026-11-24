<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Models\ProfilSekolah;
use App\Models\Sekolah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AktivitasObserver
{
    private const ABAIKAN = ['id', 'sekolah_id', 'created_at', 'updated_at'];

    public function created(Model $model): void
    {
        $baru = [];
        foreach ($model->getAttributes() as $kolom => $nilai) {
            if (! in_array($kolom, self::ABAIKAN, true) && $nilai !== null) {
                $baru[$kolom] = ['lama' => null, 'baru' => $nilai];
            }
        }

        $this->catat($model, 'dibuat', $baru ?: null);
    }

    public function updated(Model $model): void
    {
        $beda = [];
        foreach ($model->getChanges() as $kolom => $baru) {
            if (in_array($kolom, self::ABAIKAN, true)) {
                continue;
            }
            // pada event "updated", getOriginal() masih berisi nilai lama
            $beda[$kolom] = ['lama' => $model->getOriginal($kolom), 'baru' => $baru];
        }

        if ($beda !== []) {
            $this->catat($model, 'diubah', $beda);
        }
    }

    public function deleted(Model $model): void
    {
        $this->catat($model, 'dihapus', null);
    }

    private function catat(Model $model, string $aksi, ?array $perubahan): void
    {
        // Jangan catat proses seeding lewat artisan (tidak ada pengguna login)
        if (app()->runningInConsole() && ! app()->runningUnitTests() && ! Auth::check()) {
            return;
        }

        if ($model instanceof Sekolah) {
            $objek = 'sekolah';
            $sekolahId = $model->id;
            $sekolahNama = $model->nama;
        } elseif ($model instanceof ProfilSekolah) {
            $objek = 'profil';
            $sekolahId = $model->sekolah_id;
            $sekolahNama = $model->sekolah?->nama;
        } else {
            return;
        }

        $user = Auth::user();

        ActivityLog::create([
            'user_id' => $user?->id,
            'user_nama' => $user?->name ?? 'Sistem',
            'aksi' => $aksi,
            'objek' => $objek,
            'sekolah_id' => $sekolahId,
            'sekolah_nama' => $sekolahNama,
            'perubahan' => $perubahan,
        ]);
    }
}