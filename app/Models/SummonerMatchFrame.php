<?php

namespace App\Models;

use App\Enums\FrameEventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\SummonerMatchFrame
 *
 * @property int $id
 * @property int $current_timestamp
 * @property object $champion_stats
 * @property object $damage_stats
 * @property int $current_gold
 * @property int $gold_per_second
 * @property int $total_gold
 * @property int $jungle_minions_killed
 * @property int $level
 * @property int $minions_killed
 * @property int $position_x
 * @property int $position_y
 * @property int $xp
 * @property int $time_enemy_spent_controlled
 * @property int $summoner_match_id
 * @property int $match_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $death_events
 * @property-read int|null $death_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $item_events
 * @property-read int|null $item_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $kills_events
 * @property-read int|null $kills_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $level_up_skill_events
 * @property-read int|null $level_up_skill_events_count
 * @property-read \App\Models\LolMatch|null $match
 * @property-read \App\Models\SummonerMatch|null $summoner_match
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame query()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereChampionStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereCurrentGold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereCurrentTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereDamageStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereGoldPerSecond($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereJungleMinionsKilled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereMinionsKilled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame wherePositionX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame wherePositionY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereSummonerMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereTimeEnemySpentControlled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereTotalGold($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrame whereXp($value)
 *
 * @mixin \Eloquent
 */
class SummonerMatchFrame extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'current_timestamp',
        'champion_stats',
        'damage_stats',
        'current_gold',
        'gold_per_second',
        'jungle_minions_killed',
        'level',
        'minions_killed',
        'position_x',
        'position_y',
        'total_gold',
        'xp',
        'time_enemy_spent_controlled',
        'summoner_match_id',
        'match_id',
    ];

    protected $casts = [
        'champion_stats' => 'object',
        'damage_stats' => 'object',
    ];

    public function summoner_match(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function match(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LolMatch::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class, 'summoner_match_frame_id');
    }

    public function item_events(): HasMany
    {
        return $this->events()->whereIn('summoner_match_frame_events.type', [
            FrameEventType::ITEM_PURCHASED,
            FrameEventType::ITEM_SOLD,
        ]);
    }

    public function level_up_skill_events(): HasMany
    {

        return $this->events()->where('summoner_match_frame_events.type', FrameEventType::SKILL_LEVEL_UP);
    }

    public function kills_events(): HasMany
    {
        return $this->events()->where('summoner_match_frame_events.type', FrameEventType::CHAMPION_KILL);
    }

    public function death_events(): HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class, 'summoner_match_frame_victim_id', 'id')
            ->where('summoner_match_frame_events.type', FrameEventType::CHAMPION_KILL);
    }
}
