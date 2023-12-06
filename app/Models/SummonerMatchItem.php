<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\SummonerMatchItem
 *
 * @property int $id
 * @property int $item_id
 * @property int $summoner_match_id
 * @property int $position
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\SummonerMatch $summoner_match
 *
 * @method static Builder|SummonerMatchItem newModelQuery()
 * @method static Builder|SummonerMatchItem newQuery()
 * @method static Builder|SummonerMatchItem query()
 * @method static Builder|SummonerMatchItem whereId($value)
 * @method static Builder|SummonerMatchItem whereItemId($value)
 * @method static Builder|SummonerMatchItem wherePosition($value)
 * @method static Builder|SummonerMatchItem whereSummonerMatchId($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
final class SummonerMatchItem extends Model
{
    protected $table = 'summoner_match_items';

    public $timestamps = false;

    public $fillable = [
        'item_id',
        'summoner_match_id',
        'position',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function summoner_match(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }
}
