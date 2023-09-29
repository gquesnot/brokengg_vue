<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProPlayer
 *
 * @property int $id
 * @property string $slug
 * @property string|null $team_name
 * @property string $name
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayer whereTeamName($value)
 *
 * @mixin \Eloquent
 */
class ProPlayer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'slug',
        'team_name',
        'name',
    ];
}
