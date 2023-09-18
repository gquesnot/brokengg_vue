<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Version
 *
 * @property int $id
 * @property string $version
 * @method static Builder|Version newModelQuery()
 * @method static Builder|Version newQuery()
 * @method static Builder|Version query()
 * @method static Builder|Version whereId($value)
 * @method static Builder|Version whereVersion($value)
 * @mixin Eloquent
 */
class Version extends Model
{
    public $timestamps = false;

    public $fillable = [
        'id',
        'version',
    ];
}
