<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'user_nama', 'aksi', 'objek',
        'sekolah_id', 'sekolah_nama', 'perubahan',
    ];

    protected function casts(): array
    {
        return ['perubahan' => 'array'];
    }
}