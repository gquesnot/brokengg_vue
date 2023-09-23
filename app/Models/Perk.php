<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Perk
 *
 * @property int $id
 * @property string $img_url
 * @property string $name
 *
 * @method static Builder|Perk newModelQuery()
 * @method static Builder|Perk newQuery()
 * @method static Builder|Perk query()
 * @method static Builder|Perk whereId($value)
 * @method static Builder|Perk whereImgUrl($value)
 * @method static Builder|Perk whereName($value)
 *
 * @mixin Eloquent
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
