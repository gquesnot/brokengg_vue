<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Map
 *
 * @property int $id
 * @property string $description
 * @method static Builder|Map newModelQuery()
 * @method static Builder|Map newQuery()
 * @method static Builder|Map query()
 * @method static Builder|Map whereDescription($value)
 * @method static Builder|Map whereId($value)
 * @mixin Eloquent
 */
#[TypeScript]
class Map extends Model
{
    public $timestamps = false;


    public $fillable = [
        'id',
        'description'
    ];

}
