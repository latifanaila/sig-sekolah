<?php

namespace App\Enums;

enum Role: string
{
    case Admin = 'admin';
    case Dinas = 'dinas';
    case Sekolah = 'sekolah';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Admin',
            self::Dinas => 'Dinas Pendidikan',
            self::Sekolah => 'Sekolah',
        };
    }

    /** Daftar [nilai => label] untuk pilihan pada form. */
    public static function pilihan(): array
    {
        $hasil = [];
        foreach (self::cases() as $case) {
            $hasil[$case->value] = $case->label();
        }

        return $hasil;
    }
}