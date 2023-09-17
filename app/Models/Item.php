<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Item
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property array $tags
 * @property string $img_url
 *
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item query()
 * @method static Builder|Item whereDescription($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereImgUrl($value)
 * @method static Builder|Item whereName($value)
 * @method static Builder|Item whereTags($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
final class Item extends Model
{
    public $timestamps = false;

    public $fillable = [
        'id',
        'name',
        'description',
        'tags',
        'img_url',
    ];

    public $casts = [
        'tags' => 'array',
    ];

    public static function url(string $version, string $url): string
    {
        return "https://ddragon.leagueoflegends.com/cdn/{$version}/img/item/{$url}";
    }
}
