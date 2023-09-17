<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Mode
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @method static Builder|Mode newModelQuery()
 * @method static Builder|Mode newQuery()
 * @method static Builder|Mode query()
 * @method static Builder|Mode whereDescription($value)
 * @method static Builder|Mode whereId($value)
 * @method static Builder|Mode whereName($value)
 * @mixin Eloquent
 */
#[TypeScript]
class Mode extends Model
{

    public $timestamps = false;

    public $fillable = [
        'id',
        'name',
        'description'
    ];


}
