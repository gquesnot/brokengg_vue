<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SummonerSpell
 *
 * @property int $id
 * @property string $img_url
 * @property string $name
 *
 * @method static Builder|SummonerSpell newModelQuery()
 * @method static Builder|SummonerSpell newQuery()
 * @method static Builder|SummonerSpell query()
 * @method static Builder|SummonerSpell whereId($value)
 * @method static Builder|SummonerSpell whereImgUrl($value)
 * @method static Builder|SummonerSpell whereName($value)
 *
 * @mixin Eloquent
 */
class SummonerSpell extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'img_url',
        'name',
    ];
}
