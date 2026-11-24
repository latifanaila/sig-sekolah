<?php

namespace App\Support;

class Geo
{
    /** Jarak dua titik koordinat dalam meter (rumus Haversine). */
    public static function jarakMeter(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $radiusBumi = 6371000;

        $p1 = deg2rad($lat1);
        $p2 = deg2rad($lat2);
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2 + cos($p1) * cos($p2) * sin($dLng / 2) ** 2;

        return 2 * $radiusBumi * asin(min(1, sqrt($a)));
    }
}