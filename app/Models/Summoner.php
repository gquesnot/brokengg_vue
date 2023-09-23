<?php

namespace App\Models;

use App\Traits\HandleMatchDataUpdate;
use App\Traits\HandleMatchDetailUpdate;
use App\Traits\HandleMatchIdsUpdate;
use App\Traits\HandleSummonerDataUpdate;
use App\Traits\SummonerApi;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\Summoner
 *
 * @property int $id
 * @property string|null $name
 * @property int|null $profile_icon_id
 * @property int|null $revision_date
 * @property int|null $summoner_level
 * @property string|null $last_scanned_match
 * @property bool $complete
 * @property string|null $summoner_id
 * @property string|null $account_id
 * @property string|null $puuid
 * @property bool $auto_update
 * @property string|null $created_at
 * @property string|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LolMatch> $matches
 * @property-read int|null $matches_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, SummonerMatch> $summoner_matches
 * @property-read int|null $summoner_matches_count
 *
 * @method static Builder|Summoner newModelQuery()
 * @method static Builder|Summoner newQuery()
 * @method static Builder|Summoner query()
 * @method static Builder|Summoner whereAccountId($value)
 * @method static Builder|Summoner whereAutoUpdate($value)
 * @method static Builder|Summoner whereComplete($value)
 * @method static Builder|Summoner whereCreatedAt($value)
 * @method static Builder|Summoner whereId($value)
 * @method static Builder|Summoner whereLastScannedMatch($value)
 * @method static Builder|Summoner whereName($value)
 * @method static Builder|Summoner whereProfileIconId($value)
 * @method static Builder|Summoner wherePuuid($value)
 * @method static Builder|Summoner whereRevisionDate($value)
 * @method static Builder|Summoner whereSummonerId($value)
 * @method static Builder|Summoner whereSummonerLevel($value)
 * @method static Builder|Summoner whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
class Summoner extends Model
{
    use HandleMatchDataUpdate;
    use HandleMatchDetailUpdate;
    use HandleMatchIdsUpdate;
    use HandleSummonerDataUpdate;
    use SummonerApi;

    public $timestamps = false;

    public $fillable = [
        'name',
        'profile_icon_id',
        'revision_date',
        'summoner_level',
        'last_scanned_match',
        'complete',
        'summoner_id',
        'account_id',
        'puuid',
        'auto_update',
    ];

    public $casts = [
        'complete' => 'boolean',
        'auto_update' => 'boolean',
    ];

    public function updateMatches()
    {
        $this->updateSummonerMatchIds();
        $this->updateSummonerMatches();
    }

    public function matches(): HasManyThrough
    {
        return $this->hasManyThrough(LolMatch::class, SummonerMatch::class, 'summoner_id', 'id', 'id', 'match_id');
    }

    public function summoner_matches(): HasMany
    {
        return $this->hasMany(SummonerMatch::class, 'summoner_id', 'id');
    }

    public function get_encounters_count_for_summoner(Summoner $summoner)
    {

        $match_ids = $this->summoner_matches()->pluck('match_id');

        return $summoner->get_encounters_count_query($match_ids)->where('summoner_matchs.summoner_id', $summoner->id)->first();
    }

    public function get_summoner_match_query($filters, $limit = null): array
    {
        $query = SummonerMatch::whereSummonerId($this->id);
        if ($limit != null) {
            $query->limit($limit);
        }
        $query->whereHas('match', function ($sub_query) {
            $sub_query->where('updated', true)->where('is_trashed', false)->orderBy('match_creation', 'desc');
        });

        $filtered_query = $query->clone()->withWhereHas('match', function ($q) use ($filters) {
            if (Arr::has($filters, 'queue_id')) {
                $q->where('queue_id', Arr::get($filters, 'queue_id'));
            }
            if (Arr::has($filters, 'start_date')) {
                $q->where('match_creation', '>=', Arr::get($filters, 'start_date'));
            }
            if (Arr::has($filters, 'end_date')) {
                $q->where('match_creation', '<=', Arr::get($filters, 'end_date'));
            }
        });

        if (Arr::has($filters, 'champion_id')) {
            $filtered_query->whereChampionId(Arr::get($filters, 'champion_id'));
        }
        if (Arr::get($filters, 'should_filter_encounters')) {
            return [$filtered_query, $filtered_query];

        }

        return [$filtered_query, $query];

    }

    public function get_encounters_count($match_ids): array
    {

        return SummonerMatch::where('summoner_id', '!=', $this->id)
            ->whereIn('match_id', $match_ids)
            ->groupBy('summoner_id')
            ->selectRaw('summoner_id, count(id) as encounter_count')
            ->having('encounter_count', '>', 1)
            ->orderByDesc('encounter_count')
            ->pluck('encounter_count', 'summoner_id')->toArray();

    }

    public function get_encounters_count_query($match_ids): Builder|SummonerMatch|\Illuminate\Database\Query\Builder
    {
        return SummonerMatch::where('summoner_matchs.summoner_id', '!=', $this->id)
            ->join('summoners', 'summoners.id', '=', 'summoner_matchs.summoner_id')
            ->whereIn('match_id', $match_ids)
            ->groupBy(['summoner_matchs.summoner_id', 'summoners.name'])
            ->selectRaw('summoner_matchs.summoner_id, summoners.name, count(summoner_matchs.id) as encounter_count')
            ->orderByDesc('encounter_count');
    }

    public function champions(): Builder
    {
        return Champion::with(['matches' => function ($query) {
            $query->where('summoner_id', $this->id);
        }], 'matches.match');
    }

    public function champion($championId): Champion
    {
        return Champion::where('id', $championId)->with(['matches' => function ($query) {
            $query->where('summoner_id', $this->id);
        }])->first();
    }

    public function get_live_game_from_lobby_search(string $lobby_search, Collection $encounter_counts): array
    {
        $summoner_names = explode("\n", $lobby_search);
        $live_game = ['participants' => [], 'is_live' => false];
        foreach ($summoner_names as $idx => $name) {
            $name = trim(str_replace('joined the lobby', '', $name));

            $summoner = Summoner::whereName($name)->first();
            if ($name == $this->name) {
                $live_game['participants'][] = ['summoner' => $this, 'encounter_count' => 0];
            } elseif ($summoner) {
                $live_game['participants'][] = ['summoner' => $summoner, 'encounter_count' => $encounter_counts->get($summoner->id)];
            } else {
                $live_game['participants'][] = ['summoner' => (object) ['name' => $name], 'encounter_count' => 0];
            }
        }

        return $live_game;
    }

    public function get_summoner_stats(Collection $match_ids): mixed
    {

        $query = $this->summoner_matches()->whereIn('match_id', $match_ids);
        $query->select([
            DB::raw('avg(kills) as avg_kills'),
            DB::raw('avg(deaths) as avg_deaths'),
            DB::raw('avg(assists) as avg_assists'),
            DB::raw('avg(kill_participation) as avg_kill_participation'),
            DB::raw('avg(kda) as avg_kda'),
            DB::raw('sum(won) as total_win'),
            DB::raw('count(id) as total_game'),
        ]);
        $first = $query->first();
        $first['avg_kill_participation'] = round($first['avg_kill_participation'], 2);

        return $first['avg_kills'] != null ? $first : null;
    }
}
