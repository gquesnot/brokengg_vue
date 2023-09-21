<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SummonerMatchPerk
 *
 * @property int $id
 * @property int $summoner_match_id
 * @property int $defense_id
 * @property int $flex_id
 * @property int $offense_id
 * @property int $primary_style_id
 * @property int $primary_style1_id
 * @property int $primary_style2_id
 * @property int $primary_style3_id
 * @property int $sub_style_id
 * @property int $sub_style1_id
 * @property int $sub_style2_id
 * @property-read \App\Models\Perk|null $defense
 * @property-read \App\Models\Perk|null $flex
 * @property-read \App\Models\Perk|null $offense
 * @property-read \App\Models\Perk|null $primaryStyle
 * @property-read \App\Models\Perk|null $primaryStyle1
 * @property-read \App\Models\Perk|null $primaryStyle2
 * @property-read \App\Models\Perk|null $primaryStyle3
 * @property-read \App\Models\Perk|null $subStyle
 * @property-read \App\Models\Perk|null $subStyle1
 * @property-read \App\Models\Perk|null $subStyle2
 * @property-read \App\Models\SummonerMatch|null $summonerMatch
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk query()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereDefenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereFlexId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereOffenseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk wherePrimaryStyle1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk wherePrimaryStyle2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk wherePrimaryStyle3Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk wherePrimaryStyleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereSubStyle1Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereSubStyle2Id($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereSubStyleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerMatchPerk whereSummonerMatchId($value)
 *
 * @mixin \Eloquent
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
        'primary_style1_id',
        'primary_style2_id',
        'primary_style3_id',
        'sub_style_id',
        'sub_style1_id',
        'sub_style2_id',
    ];

    public function summonerMatch()
    {
        return $this->belongsTo(SummonerMatch::class);
    }

    public function defense()
    {
        return $this->belongsTo(Perk::class, 'defense_id');
    }

    public function flex()
    {
        return $this->belongsTo(Perk::class, 'flex_id');
    }

    public function offense()
    {
        return $this->belongsTo(Perk::class, 'offense_id');
    }

    public function primaryStyle()
    {
        return $this->belongsTo(Perk::class, 'primary_style_id');
    }

    public function primaryStyle1()
    {
        return $this->belongsTo(Perk::class, 'primary_style1_id');
    }

    public function primaryStyle2()
    {
        return $this->belongsTo(Perk::class, 'primary_style2_id');
    }

    public function primaryStyle3()
    {
        return $this->belongsTo(Perk::class, 'primary_style3_id');
    }

    public function subStyle()
    {
        return $this->belongsTo(Perk::class, 'sub_style_id');
    }

    public function subStyle1()
    {
        return $this->belongsTo(Perk::class, 'sub_style1_id');
    }

    public function subStyle2()
    {
        return $this->belongsTo(Perk::class, 'sub_style2_id');
    }
}
