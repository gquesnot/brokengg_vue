<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SummonerMatchPerk
 *
 * @property int $id
 * @property int $summoner_match_id
 * @property int $defense_id
 * @property int $flex_id
 * @property int $offense_id
 * @property int $primary_style_id
 * @property int $primary_selection_id
 * @property int $primary_selection1_id
 * @property int $primary_selection2_id
 * @property int $primary_selection3_id
 * @property int $sub_style_id
 * @property int $sub_selection1_id
 * @property int $sub_selection2_id
 * @property-read Perk|null $defense
 * @property-read Perk|null $flex
 * @property-read Perk|null $offense
 * @property-read Perk|null $primary_selection
 * @property-read Perk|null $primary_selection1
 * @property-read Perk|null $primary_selection2
 * @property-read Perk|null $primary_selection3
 * @property-read Perk|null $primary_style
 * @property-read Perk|null $sub_selection1
 * @property-read Perk|null $sub_selection2
 * @property-read Perk|null $sub_style
 * @property-read SummonerMatch|null $summoner_match
 *
 * @method static Builder|SummonerMatchPerk newModelQuery()
 * @method static Builder|SummonerMatchPerk newQuery()
 * @method static Builder|SummonerMatchPerk query()
 * @method static Builder|SummonerMatchPerk whereDefenseId($value)
 * @method static Builder|SummonerMatchPerk whereFlexId($value)
 * @method static Builder|SummonerMatchPerk whereId($value)
 * @method static Builder|SummonerMatchPerk whereOffenseId($value)
 * @method static Builder|SummonerMatchPerk wherePrimarySelection1Id($value)
 * @method static Builder|SummonerMatchPerk wherePrimarySelection2Id($value)
 * @method static Builder|SummonerMatchPerk wherePrimarySelection3Id($value)
 * @method static Builder|SummonerMatchPerk wherePrimarySelectionId($value)
 * @method static Builder|SummonerMatchPerk wherePrimaryStyleId($value)
 * @method static Builder|SummonerMatchPerk whereSubSelection1Id($value)
 * @method static Builder|SummonerMatchPerk whereSubSelection2Id($value)
 * @method static Builder|SummonerMatchPerk whereSubStyleId($value)
 * @method static Builder|SummonerMatchPerk whereSummonerMatchId($value)
 *
 * @mixin Eloquent
 */
class SummonerMatchPerk extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'summoner_match_id',
        'defense_id',
        'flex_id',
        'offense_id',
        'primary_style_id',
        'primary_selection_id',
        'primary_selection1_id',
        'primary_selection2_id',
        'primary_selection3_id',
        'sub_style_id',
        'sub_selection1_id',
        'sub_selection2_id',
    ];

    public function summoner_match(): BelongsTo
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function defense(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'defense_id');
    }

    public function flex(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'flex_id');
    }

    public function offense(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'offense_id');
    }

    public function primary_style(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'primary_style_id');
    }

    public function sub_style(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'sub_style_id');
    }

    public function primary_selection(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'primary_selection_id');
    }

    public function primary_selection1(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'primary_selection1_id');
    }

    public function primary_selection2(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'primary_selection2_id');
    }

    public function primary_selection3(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'primary_selection3_id');
    }

    public function sub_selection1(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'sub_selection1_id');
    }

    public function sub_selection2(): BelongsTo
    {
        return $this->belongsTo(Perk::class, 'sub_selection2_id');
    }
}
