<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\LolMatch
 *
 * @property int $id
 * @property bool $updated
 * @property string $match_id
 * @property int|null $mode_id
 * @property int|null $map_id
 * @property int|null $queue_id
 * @property \Illuminate\Support\Carbon|null $match_creation
 * @property \Illuminate\Support\Carbon|null $match_end
 * @property string|null $match_duration
 * @property bool $is_trashed
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Map|null $map
 * @property-read \App\Models\Mode|null $mode
 * @property-read Collection<int, \App\Models\SummonerMatch> $participants
 * @property-read int|null $participants_count
 * @property-read \App\Models\Queue|null $queue
 * @property-read \App\Models\Version|null $version
 *
 * @method static Builder|LolMatch newModelQuery()
 * @method static Builder|LolMatch newQuery()
 * @method static Builder|LolMatch query()
 * @method static Builder|LolMatch whereCreatedAt($value)
 * @method static Builder|LolMatch whereId($value)
 * @method static Builder|LolMatch whereIsTrashed($value)
 * @method static Builder|LolMatch whereMapId($value)
 * @method static Builder|LolMatch whereMatchCreation($value)
 * @method static Builder|LolMatch whereMatchDuration($value)
 * @method static Builder|LolMatch whereMatchEnd($value)
 * @method static Builder|LolMatch whereMatchId($value)
 * @method static Builder|LolMatch whereModeId($value)
 * @method static Builder|LolMatch whereQueueId($value)
 * @method static Builder|LolMatch whereUpdated($value)
 * @method static Builder|LolMatch whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
final class LolMatch extends Model
{
    protected $table = 'lol_matchs';

    public $fillable = [
        'updated',
        'match_id',
        'mode_id',
        'map_id',
        'queue_id',
        'match_creation',
        'match_end',
        'match_duration',
        'is_trashed',
        'created_at',
        'updated_at',
    ];

    public $casts = [
        'match_creation' => 'datetime',
        'match_end' => 'datetime',
    ];

    public function sinceMatchEnd()
    {
        return $this->match_end->diffForHumans(Carbon::now());
    }

    public function participants(): HasMany
    {
        return $this->hasMany(SummonerMatch::class, 'match_id', 'id');
    }

    public function mode(): HasOne
    {
        return $this->hasOne(Mode::class, 'id', 'mode_id');
    }

    public function queue(): HasOne
    {
        return $this->hasOne(Queue::class, 'id', 'queue_id');
    }

    public function map(): HasOne
    {
        return $this->hasOne(Map::class, 'id', 'map_id');
    }

    public function version(): HasOne
    {
        return $this->hasOne(Version::class, 'id', 'version_id');
    }
}
