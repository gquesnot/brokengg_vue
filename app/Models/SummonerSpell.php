<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\SummonerSpell
 *
 * @property int $id
 * @property string $img_url
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell query()
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SummonerSpell whereName($value)
 *
 * @mixin \Eloquent
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
