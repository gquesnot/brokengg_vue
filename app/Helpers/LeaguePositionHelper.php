<?php

namespace App\Helpers;

use App\Enums\LeagueRankType;
use App\Enums\LeagueTierType;

class LeaguePositionHelper
{
    public static function getOverallPosition(LeagueTierType $tier, LeagueRankType $rank): int
    {
        $tiers = [
            LeagueTierType::IRON->name => 0,
            LeagueTierType::BRONZE->name => 4,
            LeagueTierType::SILVER->name => 8,
            LeagueTierType::GOLD->name => 12,
            LeagueTierType::PLATINUM->name => 16,
            LeagueTierType::EMERALD->name => 20,
            LeagueTierType::DIAMOND->name => 24,
            LeagueTierType::MASTER->name => 28,
            LeagueTierType::GRANDMASTER->name => 29,
            LeagueTierType::CHALLENGER->name => 30,
        ];

        $tier_value = $tiers[$tier->name];
        if ($tier->name == LeagueTierType::CHALLENGER->name || $tier->name == LeagueTierType::GRANDMASTER->name || $tier->name == LeagueTierType::MASTER->name) {
            $rank_value = 0;
        } else {
            $rank_value = 4 - $rank->toInt();
        }

        return $tier_value + $rank_value;
    }

    /**
     * @return array<LeagueTierType, LeagueRankType>
     */
    public static function getTierRankFromOverallPosition(int $overall_position): array
    {
        $tier = null;
        $rank = null;
        if ($overall_position >= 30) {
            $tier = LeagueTierType::CHALLENGER;
            $rank = LeagueRankType::I;
        } elseif ($overall_position >= 29) {
            $tier = LeagueTierType::GRANDMASTER;
            $rank = LeagueRankType::I;
        } elseif ($overall_position >= 28) {
            $tier = LeagueTierType::MASTER;
            $rank = LeagueRankType::I;
        } elseif ($overall_position >= 24) {
            $tier = LeagueTierType::DIAMOND;
            $rank = LeagueRankType::from(4 - ($overall_position - 24));
        } elseif ($overall_position >= 20) {
            $tier = LeagueTierType::EMERALD;
            $rank = LeagueRankType::from(4 - ($overall_position - 20));
        } elseif ($overall_position >= 16) {
            $tier = LeagueTierType::PLATINUM;
            $rank = LeagueRankType::from(4 - ($overall_position - 16));
        } elseif ($overall_position >= 12) {
            $tier = LeagueTierType::GOLD;
            $rank = LeagueRankType::from(4 - ($overall_position - 12));
        } elseif ($overall_position >= 8) {
            $tier = LeagueTierType::SILVER;
            $rank = LeagueRankType::from(4 - ($overall_position - 8));
        } elseif ($overall_position >= 4) {
            $tier = LeagueTierType::BRONZE;
            $rank = LeagueRankType::from(4 - ($overall_position - 4));
        } elseif ($overall_position >= 0) {
            $tier = LeagueTierType::IRON;
            $rank = LeagueRankType::from(4 - $overall_position);
        }

        return [
            $tier,
            $rank,
        ];
    }
}
