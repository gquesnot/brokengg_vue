<?php


namespace App\Models;

use App\Enums\FrameEventType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * @property-read Collection<int, SummonerMatchFrameEvent> $death_events
 * @property-read int|null $death_events_count
 * @property-read Collection<int, SummonerMatchFrameEvent> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, SummonerMatchFrameEvent> $item_events
 * @property-read int|null $item_events_count
 * @property-read Collection<int, SummonerMatchFrameEvent> $kills_events
 * @property-read int|null $kills_events_count
 * @property-read Collection<int, SummonerMatchFrameEvent> $level_up_skill_events
 * @property-read int|null $level_up_skill_events_count
 * @property-read LolMatch|null $match
 * @property-read SummonerMatch|null $summoner_match
 * @method static Builder|SummonerMatchFrame newModelQuery()
 * @method static Builder|SummonerMatchFrame newQuery()
 * @method static Builder|SummonerMatchFrame query()
 * @method static Builder|SummonerMatchFrame whereChampionStats($value)
 * @method static Builder|SummonerMatchFrame whereCurrentGold($value)
 * @method static Builder|SummonerMatchFrame whereCurrentTimestamp($value)
 * @method static Builder|SummonerMatchFrame whereDamageStats($value)
 * @method static Builder|SummonerMatchFrame whereGoldPerSecond($value)
 * @method static Builder|SummonerMatchFrame whereId($value)
 * @method static Builder|SummonerMatchFrame whereJungleMinionsKilled($value)
 * @method static Builder|SummonerMatchFrame whereLevel($value)
 * @method static Builder|SummonerMatchFrame whereMatchId($value)
 * @method static Builder|SummonerMatchFrame whereMinionsKilled($value)
 * @method static Builder|SummonerMatchFrame wherePositionX($value)
 * @method static Builder|SummonerMatchFrame wherePositionY($value)
 * @method static Builder|SummonerMatchFrame whereSummonerMatchId($value)
 * @method static Builder|SummonerMatchFrame whereTimeEnemySpentControlled($value)
 * @method static Builder|SummonerMatchFrame whereTotalGold($value)
 * @method static Builder|SummonerMatchFrame whereXp($value)
 * @mixin Eloquent
 */
final class SummonerMatchFrame extends Model
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

    public function summoner_match(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function match(): BelongsTo
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
