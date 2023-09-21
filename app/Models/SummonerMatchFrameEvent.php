<?php

namespace App\Models;

use App\Enums\FrameEventType;
use App\Enums\LevelUpType;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SummonerMatchFrameEvent
 *
 * @property int $id
 * @property int $summoner_match_id
 * @property int $summoner_match_frame_id
 * @property int $current_timestamp
 * @property FrameEventType $type
 * @property int|null $item_id
 * @property int|null $before_id
 * @property int|null $after_id
 * @property int|null $gold_gain
 * @property int|null $summoner_match_victim_id
 * @property int|null $summoner_match_frame_victim_id
 * @property int|null $position_x
 * @property int|null $position_y
 * @property int|null $skill_slot
 * @property LevelUpType|null $level_up_type
 * @property int|null $level
 * @property-read \App\Models\Item|null $item
 * @property-read \App\Models\Item|null $item_after
 * @property-read \App\Models\Item|null $item_before
 * @property-read \App\Models\SummonerMatch|null $summoner_match
 * @property-read \App\Models\SummonerMatchFrame|null $summoner_match_frame
 * @property-read \App\Models\SummonerMatchFrame|null $summoner_match_frame_victim
 * @property-read \App\Models\SummonerMatch|null $summoner_match_victim
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereAfterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereBeforeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereCurrentTimestamp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereGoldGain($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereLevelUpType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent wherePositionX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent wherePositionY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereSkillSlot($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereSummonerMatchFrameId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereSummonerMatchFrameVictimId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereSummonerMatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereSummonerMatchVictimId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchFrameEvent whereType($value)
 *
 * @mixin \Eloquent
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
        'before_id',
        'after_id',
        'gold_gain',
    ];

    protected $casts = [
        'type' => FrameEventType::class,
        'level_up_type' => LevelUpType::class,

    ];

    public function summoner_match(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function summoner_match_frame(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SummonerMatchFrame::class);
    }

    public function summoner_match_victim(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class, 'summoner_match_victim_id');
    }

    public function summoner_match_frame_victim(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SummonerMatchFrame::class, 'summoner_match_frame_victim_id');
    }

    public function item(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function item_after(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'after_id');
    }

    public function item_before(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class, 'before_id');
    }
}
