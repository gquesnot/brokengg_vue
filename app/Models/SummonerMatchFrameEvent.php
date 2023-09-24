<?php

namespace App\Models;

use App\Enums\FrameEventType;
use App\Enums\LevelUpType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SummonerMatchFrameEvent
 *
 * @property int $id
 * @property int $summoner_match_id
 * @property int $summoner_match_frame_id
 * @property int $current_timestamp
 * @property FrameEventType $type
 * @property int|null $item_id
 * @property int|null $summoner_match_victim_id
 * @property int|null $summoner_match_frame_victim_id
 * @property int|null $position_x
 * @property int|null $position_y
 * @property int|null $skill_slot
 * @property LevelUpType|null $level_up_type
 * @property int|null $level
 * @property-read \App\Models\Item|null $item
 * @property-read \App\Models\SummonerMatch|null $summoner_match
 * @property-read \App\Models\SummonerMatchFrame|null $summoner_match_frame
 * @property-read \App\Models\SummonerMatchFrame|null $summoner_match_frame_victim
 * @property-read \App\Models\SummonerMatch|null $summoner_match_victim
 *
 * @method static Builder|SummonerMatchFrameEvent newModelQuery()
 * @method static Builder|SummonerMatchFrameEvent newQuery()
 * @method static Builder|SummonerMatchFrameEvent query()
 * @method static Builder|SummonerMatchFrameEvent whereCurrentTimestamp($value)
 * @method static Builder|SummonerMatchFrameEvent whereId($value)
 * @method static Builder|SummonerMatchFrameEvent whereItemId($value)
 * @method static Builder|SummonerMatchFrameEvent whereLevel($value)
 * @method static Builder|SummonerMatchFrameEvent whereLevelUpType($value)
 * @method static Builder|SummonerMatchFrameEvent wherePositionX($value)
 * @method static Builder|SummonerMatchFrameEvent wherePositionY($value)
 * @method static Builder|SummonerMatchFrameEvent whereSkillSlot($value)
 * @method static Builder|SummonerMatchFrameEvent whereSummonerMatchFrameId($value)
 * @method static Builder|SummonerMatchFrameEvent whereSummonerMatchFrameVictimId($value)
 * @method static Builder|SummonerMatchFrameEvent whereSummonerMatchId($value)
 * @method static Builder|SummonerMatchFrameEvent whereSummonerMatchVictimId($value)
 * @method static Builder|SummonerMatchFrameEvent whereType($value)
 *
 * @mixin Eloquent
 */
class SummonerMatchFrameEvent extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'summoner_match_id',
        'summoner_match_frame_id',
        'current_timestamp',
        'type',

        'summoner_match_victim_id',
        'summoner_match_frame_victim_id',
        'position_x',
        'position_y',

        'skill_slot',
        'level_up_type',

        'level',

        'item_id',
    ];

    protected $casts = [
        'type' => FrameEventType::class,
        'level_up_type' => LevelUpType::class,

    ];

    public function summoner_match(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function summoner_match_frame(): BelongsTo
    {
        return $this->belongsTo(SummonerMatchFrame::class);
    }

    public function summoner_match_victim(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class, 'summoner_match_victim_id');
    }

    public function summoner_match_frame_victim(): BelongsTo
    {
        return $this->belongsTo(SummonerMatchFrame::class, 'summoner_match_frame_victim_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
