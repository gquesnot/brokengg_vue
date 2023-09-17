<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;


/**
 * App\Models\ItemSummonerMatch
 *
 * @property int $item_id
 * @property int $summoner_match_id
 * @property int $position
 * @property-read Item|null $item
 * @property-read SummonerMatch|null $summonerMatch
 * @method static Builder|ItemSummonerMatch newModelQuery()
 * @method static Builder|ItemSummonerMatch newQuery()
 * @method static Builder|ItemSummonerMatch query()
 * @method static Builder|ItemSummonerMatch whereItemId($value)
 * @method static Builder|ItemSummonerMatch wherePosition($value)
 * @method static Builder|ItemSummonerMatch whereSummonerMatchId($value)
 * @mixin Eloquent
 */
#[TypeScript]
final class ItemSummonerMatch extends Model
{

    protected $table = 'item_summoner_matchs';

    public $timestamps = false;


    public $fillable = [
        'item_id',
        'summoner_match_id',
        'position'
    ];


    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function summonerMatch(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

}
