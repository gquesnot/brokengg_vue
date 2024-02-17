<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Queue
 *
 * @property int $id
 * @property string $description
 * @property mixed $name
 *
 * @method static Builder|Queue newModelQuery()
 * @method static Builder|Queue newQuery()
 * @method static Builder|Queue query()
 * @method static Builder|Queue whereDescription($value)
 * @method static Builder|Queue whereId($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
class Queue extends Model
{
    public $timestamps = false;

    public $fillable = [
        'id',
        'description',
    ];

    public function name(): Attribute
    {
        return Attribute::make(
            get: function () {
                return str_replace('Pick', '', str_replace(' games', '', $this->description));
            },
            set: function ($value) {
                $this->attributes['description'] = $value;
            }
        );
    }
}
