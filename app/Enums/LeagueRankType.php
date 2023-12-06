<?php

namespace App\Enums;

enum LeagueRankType: string
{
    case I = 'I';
    case II = 'II';
    case III = 'III';
    case IV = 'IV';

    public function toInt(): int
    {
        return match ($this) {
            self::I => 1,
            self::II => 2,
            self::III => 3,
            self::IV => 4,
        };
    }
}
