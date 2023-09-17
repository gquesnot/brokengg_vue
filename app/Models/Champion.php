<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Champion
 *
 * @property int $id
 * @property string $name
 * @property string $title
 * @property string $img_url
 * @property array|null $stats
 * @method static Builder|Champion newModelQuery()
 * @method static Builder|Champion newQuery()
 * @method static Builder|Champion query()
 * @method static Builder|Champion whereId($value)
 * @method static Builder|Champion whereImgUrl($value)
 * @method static Builder|Champion whereName($value)
 * @method static Builder|Champion whereStats($value)
 * @method static Builder|Champion whereTitle($value)
 * @mixin Eloquent
 */
#[TypeScript]
final class Champion extends Model
{
    public $timestamps = false;

    public $fillable = [
        'id',
        'name',
        'title',
        'img_url',
        'stats'
    ];


    public $casts = [
        'stats' => 'array'
    ];


    public static function url(string $version, string $url)
    {
        return "https://ddragon.leagueoflegends.com/cdn/{$version}/img/champion/{$url}";
    }
}
