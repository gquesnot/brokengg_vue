<?php

namespace App\Models;

use App\Enums\LeagueRankType;
use App\Enums\LeagueTierType;
use App\Enums\LeagueType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SummonerLeague
 *
 * @property int $id
 * @property LeagueType $type
 * @property LeagueTierType $tier
 * @property LeagueRankType $rank
 * @property int|null $overall_position
 * @property int $summoner_id
 * @property-read \App\Models\Summoner $summoner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague query()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereOverallPosition($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereSummonerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereTier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerLeague whereType($value)
 *
 * @mixin \Eloquent
 */
class SummonerLeague extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type',
        'tier',
        'rank',
        'summoner_id',
        'overall_position',
    ];

    protected $casts = [
        'type' => LeagueType::class,
        'tier' => LeagueTierType::class,
        'rank' => LeagueRankType::class,
    ];

    public function summoner(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(summoner::class);
    }
}
