<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

/**
 * App\Models\SummonerMatch
 *
 * @property int $id
 * @property bool $won
 * @property float|null $kill_participation
 * @property int $champ_level
 * @property float|null $kda
 * @property int $assists
 * @property int $deaths
 * @property int $kills
 * @property int $minions_killed
 * @property int $largest_killing_spree
 * @property int $champion_id
 * @property int $summoner_id
 * @property int $match_id
 * @property int $double_kills
 * @property int $triple_kills
 * @property int $quadra_kills
 * @property int $penta_kills
 * @property int $total_damage_dealt_to_champions
 * @property int $gold_earned
 * @property int $total_damage_taken
 * @property-read \App\Models\Champion|null $champion
 * @property-read Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\LolMatch|null $match
 * @property-read Collection<int, SummonerMatch> $otherParticipants
 * @property-read int|null $other_participants_count
 * @property-read \App\Models\Summoner|null $summoner
 *
 * @method static Builder|SummonerMatch championsCalc()
 * @method static Builder|SummonerMatch loadAll()
 * @method static Builder|SummonerMatch loadPartial()
 * @method static Builder|SummonerMatch newModelQuery()
 * @method static Builder|SummonerMatch newQuery()
 * @method static Builder|SummonerMatch query()
 * @method static Builder|SummonerMatch whereAssists($value)
 * @method static Builder|SummonerMatch whereChampLevel($value)
 * @method static Builder|SummonerMatch whereChampionId($value)
 * @method static Builder|SummonerMatch whereDeaths($value)
 * @method static Builder|SummonerMatch whereDoubleKills($value)
 * @method static Builder|SummonerMatch whereGoldEarned($value)
 * @method static Builder|SummonerMatch whereId($value)
 * @method static Builder|SummonerMatch whereKda($value)
 * @method static Builder|SummonerMatch whereKillParticipation($value)
 * @method static Builder|SummonerMatch whereKills($value)
 * @method static Builder|SummonerMatch whereLargestKillingSpree($value)
 * @method static Builder|SummonerMatch whereMatchId($value)
 * @method static Builder|SummonerMatch whereMinionsKilled($value)
 * @method static Builder|SummonerMatch wherePentaKills($value)
 * @method static Builder|SummonerMatch whereQuadraKills($value)
 * @method static Builder|SummonerMatch whereSummonerId($value)
 * @method static Builder|SummonerMatch whereTotalDamageDealtToChampions($value)
 * @method static Builder|SummonerMatch whereTotalDamageTaken($value)
 * @method static Builder|SummonerMatch whereTripleKills($value)
 * @method static Builder|SummonerMatch whereWon($value)
 *
 * @mixin Eloquent
 */
#[TypeScript]
final class SummonerMatch extends Model
{
    protected $table = 'summoner_matchs';

    public $timestamps = false;

    public $fillable = [
        'won',
        'kill_participation',
        'champ_level',
        'kda',
        'assists',
        'deaths',
        'kills',
        'minions_killed',
        'largest_killing_spree',
        'champion_id',
        'summoner_id',
        'match_id',
        'double_kills',
        'triple_kills',
        'quadra_kills',
        'penta_kills',
        'total_damage_dealt_to_champions',
        'gold_earned',
        'total_damage_taken',
    ];

    public $casts = [
        'won' => 'boolean',
    ];

    public function scopeLoadAll(Builder $query)
    {
        $query->with([
            'match:id,match_id,match_duration,match_end,mode_id,map_id,queue_id',
            'match.queue:id,description',
            'match.map:id,description',
            'match.mode:id,description',
            'match.participants:id,summoner_id,champion_id,match_id,won,kda,kills,deaths,assists,minions_killed,total_damage_dealt_to_champions,total_damage_taken,gold_earned',
            'match.participants.summoner:id,name',
            'match.participants.champion:id,name,img_url',
            'match.participants.items:id,img_url',
        ]);
    }

    public function scopeLoadPartial(Builder $query)
    {
        $query->with([
            'match:id,match_id,match_duration,match_end,mode_id,map_id,queue_id',
            'match.queue:id,description',
            'match.map:id,description',
            'match.mode:id,description',
            'items:id,img_url',
            'champion:id,name,img_url',
            'match.participants:id,summoner_id,champion_id,match_id,won',
            'match.participants.summoner:id,name',
            'match.participants.champion:id,name,img_url',
        ]);
    }

    public function csPerMinute(): Attribute
    {
        return Attribute::make(
            get: function () {
                $minutes = Carbon::createFromTimeString($this->match->getRawOriginal('match_duration'))->minute;

                return $minutes > 0 ? round($this->minions_killed / $minutes) : $this->minions_killed;
            }
        );
    }

    public function winrate(): Attribute
    {
        return Attribute::make(
            get: fn () => round($this->wins / $this->total * 100, 2),
        );
    }

    public function loses(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->total - $this->wins,
        );
    }

    public function avgKda(): Attribute
    {
        return Attribute::make(

            get: function () {
                $avg_death = $this->avg_deaths == 0 ? 1 : $this->avg_deaths;

                return round(($this->avg_kills + $this->avg_assists) / $avg_death, 2);
            }
        );
    }

    public function wins(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function avgKills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => round(floatval($value), 2),
        );
    }

    public function avgDeaths(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => round(floatval($value), 2),
        );
    }

    public function avgAssists(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => round(floatval($value), 2),
        );
    }

    public function avgCs(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function avgDamageDealtToChampions(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function avgGold(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function avgDamageTaken(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function totalDoubleKills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function totalTripleKills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function totalQuadraKills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function totalPentaKills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => intval($value),
        );
    }

    public function scopeChampionsCalc(Builder $query)
    {
        return $query->select(
            'champion_id',
            DB::raw('count(*) as total'),
            DB::raw('sum(won) as wins'),
            DB::raw('avg(kills) as avg_kills'),
            DB::raw('avg(deaths) as avg_deaths'),
            DB::raw('avg(assists) as avg_assists'),
            DB::raw('avg(minions_killed) as avg_cs'),
            DB::raw('max(kills) as max_kills'),
            DB::raw('max(deaths) as max_deaths'),
            DB::raw('max(assists) as max_assists'),
            DB::raw('avg(total_damage_dealt_to_champions) as avg_damage_dealt_to_champions'),
            DB::raw('avg(gold_earned) as avg_gold'),
            DB::raw('avg(total_damage_taken) as avg_damage_taken'),
            DB::raw('sum(double_kills) as total_double_kills'),
            DB::raw('sum(triple_kills) as total_triple_kills'),
            DB::raw('sum(quadra_kills) as total_quadra_kills'),
            DB::raw('sum(penta_kills) as total_penta_kills'),
        )
            ->with('champion:id,name,img_url')
            ->groupBy('champion_id')
            ->orderBy('total', 'desc');
    }

    #[TypeScript]
    public function items(): HasManyThrough
    {
        return $this->hasManyThrough(Item::class, ItemSummonerMatch::class, 'summoner_match_id', 'id', 'id', 'item_id')->orderBy('position');
    }

    #[TypeScript]
    public function summoner(): HasOne
    {
        return $this->hasOne(Summoner::class, 'id', 'summoner_id');
    }

    #[TypeScript]
    public function match(): HasOne
    {
        return $this->hasOne(LolMatch::class, 'id', 'match_id');
    }

    #[TypeScript]
    public function otherParticipants(): HasMany
    {
        return $this->hasMany(SummonerMatch::class, 'match_id', 'match_id');
    }

    #[TypeScript]
    public function champion(): HasOne
    {
        return $this->hasOne(Champion::class, 'id', 'champion_id');
    }
}
