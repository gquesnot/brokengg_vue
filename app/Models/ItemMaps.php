<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ItemMaps
 *
 * @property int $map_id
 * @property int $item_id
 * @property-read \App\Models\Item $item
 * @property-read \App\Models\Map $map
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ItemMaps newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemMaps newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemMaps query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemMaps whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemMaps whereMapId($value)
 *
 * @mixin \Eloquent
 */
class ItemMaps extends Model
{
    public $timestamps = false;

    public $fillable = [
        'map_id',
        'item_id',
    ];

    protected $primaryKey = ['map_id', 'item_id'];

    public $incrementing = false;

    public $casts = [];

    public function map(): BelongsTo
    {
        return $this->belongsTo(Map::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
