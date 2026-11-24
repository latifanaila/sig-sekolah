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
            self::Admin => 'Administrator',
            self::Dinas => 'Dinas Pendidikan',
            self::Sekolah => 'Operator Sekolah',
        };
    }
        public static function pilihan(): array
    {
        $hasil = [];

        foreach (self::cases() as $role) {
            $hasil[$role->value] = $role->label();
        }

        return $hasil;
    }
}