<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Perk
 *
 * @property int $id
 * @property string $img_url
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Perk newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perk newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Perk query()
 * @method static \Illuminate\Database\Eloquent\Builder|Perk whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perk whereImgUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Perk whereName($value)
 *
 * @mixin \Eloquent
 */
class Perk extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'img_url',
        'name',
    ];
}
