<?php

namespace App\Models;

use App\Enums\FrameEventType;
use Illuminate\Database\Eloquent\Model;

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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $item_events
 * @property-read int|null $item_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $kill_events
 * @property-read int|null $kill_events_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $level_up_events
 * @property-read int|null $level_up_events_count
 * @property-read \App\Models\LolMatch|null $match
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $skill_level_up_events
 * @property-read int|null $skill_level_up_events_count
 * @property-read \App\Models\SummonerMatch|null $summoner_match
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SummonerMatchFrameEvent> $victim_events
 * @property-read int|null $victim_events_count
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

    public function events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class);
    }

    public function item_events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class)->whereIn('type', FrameEventType::itemTypes());
    }

    public function skill_level_up_events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class)->where('type', FrameEventType::SKILL_LEVEL_UP);
    }

    public function level_up_events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class)->where('type', FrameEventType::LEVEL_UP);
    }

    public function kill_events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class)->where('type', FrameEventType::CHAMPION_KILL);
    }

    public function victim_events(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class, 'summoner_match_frame_victim_id')->where('type', FrameEventType::CHAMPION_KILL);
    }
}
