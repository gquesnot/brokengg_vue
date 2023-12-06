<?php

namespace App\Models;

use App\Enums\FrameEventType;
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
 * @property int|null $wards_placed
 * @property int $summoner_spell1_id
 * @property int $summoner_spell2_id
 * @property-read \App\Models\Champion|null $champion
 * @property-read Collection<int, \App\Models\SummonerMatchFrameEvent> $death_events
 * @property-read int|null $death_events_count
 * @property-read Collection<int, \App\Models\SummonerMatchFrameEvent> $events
 * @property-read int|null $events_count
 * @property-read Collection<int, \App\Models\SummonerMatchFrame> $frames
 * @property-read int|null $frames_count
 * @property-read Collection<int, \App\Models\SummonerMatchFrameEvent> $item_events
 * @property-read int|null $item_events_count
 * @property-read Collection<int, \App\Models\Item> $items
 * @property-read int|null $items_count
 * @property-read Collection<int, \App\Models\SummonerMatchFrameEvent> $kills_events
 * @property-read int|null $kills_events_count
 * @property-read Collection<int, \App\Models\SummonerMatchFrameEvent> $level_up_skill_events
 * @property-read int|null $level_up_skill_events_count
 * @property-read \App\Models\LolMatch|null $match
 * @property-read \App\Models\SummonerMatchPerk|null $perks
 * @property-read \App\Models\Summoner|null $summoner
 * @property-read \App\Models\SummonerSpell|null $summoner_spell1
 * @property-read \App\Models\SummonerSpell|null $summoner_spell2
 *
 * @method static Builder|SummonerMatch championsCalc()
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
 * @method static Builder|SummonerMatch whereSummonerSpell1Id($value)
 * @method static Builder|SummonerMatch whereSummonerSpell2Id($value)
 * @method static Builder|SummonerMatch whereTotalDamageDealtToChampions($value)
 * @method static Builder|SummonerMatch whereTotalDamageTaken($value)
 * @method static Builder|SummonerMatch whereTripleKills($value)
 * @method static Builder|SummonerMatch whereWardsPlaced($value)
 * @method static Builder|SummonerMatch whereWon($value)
 * @method static Builder|SummonerMatch withAll()
 * @method static Builder|SummonerMatch withDetail()
 * @method static Builder|SummonerMatch withPartial()
 *
 * @mixin Eloquent
 */
#[TypeScript]
final class SummonerMatch extends Model
{
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
        'wards_placed',
        'total_damage_dealt_to_champions',
        'gold_earned',
        'total_damage_taken',
        'summoner_spell1_id',
        'summoner_spell2_id',
    ];

    public $casts = [
        'won' => 'boolean',
        'kda' => 'float',
        'kill_participation' => 'float',
    ];

    protected $table = 'summoner_matchs';

    public function scopeWithAll(Builder $query): void
    {
        $query->select([
            'summoner_spell1_id',
            'summoner_spell2_id',
            'id',
            'won',
            'match_id',
            'summoner_id',
            'champion_id',
        ]);
        $query->with([
            'match:id,match_id,match_duration,match_end,mode_id,map_id,queue_id',
            'match.queue:id,description',
            'match.map:id,description',
            'match.mode:id,description',
            'match.participants:id,summoner_id,champion_id,match_id,won,kda,kills,deaths,assists,minions_killed,total_damage_dealt_to_champions,total_damage_taken,gold_earned,wards_placed,summoner_spell1_id,summoner_spell2_id,champ_level',
            'match.participants.summoner:id,name,summoner_level',
            'match.participants.summoner.solo_q:id,rank,tier,overall_position,summoner_id',
            'match.participants.champion:id,name,img_url',
            'match.participants.items:items.id as id,img_url',
            'match.participants.summoner_spell1:id,img_url',
            'match.participants.summoner_spell2:id,img_url',
            'match.participants.perks:summoner_match_id,primary_selection_id,sub_style_id',
            'match.participants.perks.primary_selection:id,img_url',
            'match.participants.perks.sub_style:id,img_url',
        ]);
    }

    public function scopeWithPartial(Builder $query): void
    {
        $query->select([
            'champ_level',
            'summoner_spell1_id',
            'summoner_spell2_id',
            'summoner_matchs.id',
            'won',
            'match_id',
            'summoner_id',
            'champion_id',
            'kda',
            'kills',
            'deaths',
            'assists',
            'minions_killed',
            'wards_placed',
            'kill_participation',
        ]);
        $query->with([
            'summoner_spell1:id,img_url',
            'summoner_spell2:id,img_url',
            'perks:summoner_match_id,primary_selection_id,sub_style_id',
            'perks.primary_selection:id,img_url',
            'perks.sub_style:id,img_url',
            'match:id,match_id,match_duration,match_end,mode_id,map_id,queue_id',
            'match.queue:id,description',
            'match.map:id,description',
            'match.mode:id,description',
            'items:items.id as id,img_url',
            'champion:id,name,img_url',
            'match.participants:id,summoner_id,champion_id,match_id,won',
            'match.participants.summoner:id,name,summoner_level',
            'match.participants.summoner.solo_q:id,rank,tier,overall_position,summoner_id',
            'match.participants.summoner.pro_player:pro_players.id as id,slug,team_name,name',
            'match.participants.champion:id,name,img_url',
        ]);
    }

    public function scopeWithDetail(Builder $query): void
    {
        $query->with([
            'frames:id,match_id,summoner_match_id,current_timestamp',
            'frames.item_events' => function ($query): void {
                $query->select(
                    ['summoner_match_frame_id',
                        'item_id',
                        'type',
                        DB::raw('count(id) as item_count'),
                    ]
                )->groupBy(['summoner_match_frame_id', 'item_id', 'type']);
            },
            'frames.item_events.item:id,name,img_url',
            'frames.level_up_skill_events:id,skill_slot,level_up_type,summoner_match_id,summoner_match_frame_id',
            'perks.defense:id,img_url',
            'perks.offense:id,img_url',
            'perks.flex:id,img_url',
            'perks.primary_style:id,img_url,name',
            'perks.primary_selection:id,img_url,name',
            'perks.primary_selection1:id,img_url',
            'perks.primary_selection2:id,img_url',
            'perks.primary_selection3:id,img_url',
            'perks.sub_style:id,img_url,name',
            'perks.sub_selection1:id,img_url',
            'perks.sub_selection2:id,img_url',
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
                $avg_death = $this->avg_deaths === 0 ? 1 : $this->avg_deaths;

                return round(($this->avg_kills + $this->avg_assists) / $avg_death, 2);
            }
        );
    }

    public function wins(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function avgKills(): Attribute
    {
        return Attribute::make(
            get: fn($value) => round((float)$value, 2),
        );
    }

    public function avgDeaths(): Attribute
    {
        return Attribute::make(
            get: fn($value) => round((float)$value, 2),
        );
    }

    public function avgAssists(): Attribute
    {
        return Attribute::make(
            get: fn($value) => round((float)$value, 2),
        );
    }

    public function avgCs(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function avgDamageDealtToChampions(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function avgGold(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function avgDamageTaken(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function totalDoubleKills(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function totalTripleKills(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function totalQuadraKills(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function totalPentaKills(): Attribute
    {
        return Attribute::make(
            get: fn($value) => (int)$value,
        );
    }

    public function scopeChampionsCalc(Builder $query)
    {
        return $query->select(
            'champion_id',
            DB::raw('count(id) as total'),
            DB::raw('sum(CASE WHEN won THEN 1 ELSE 0 END) as total_win'),
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
        return $this->hasManyThrough(Item::class, SummonerMatchItem::class, 'summoner_match_id', 'id', 'id', 'item_id')->orderBy('position');
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
    public function champion(): HasOne
    {
        return $this->hasOne(Champion::class, 'id', 'champion_id');
    }

    public function perks(): HasOne
    {
        return $this->hasOne(SummonerMatchPerk::class, 'summoner_match_id', 'id');
    }

    public function summoner_spell1(): HasOne
    {
        return $this->hasOne(SummonerSpell::class, 'id', 'summoner_spell1_id');
    }

    public function summoner_spell2(): HasOne
    {
        return $this->hasOne(SummonerSpell::class, 'id', 'summoner_spell2_id');
    }

    public function frames(): HasMany
    {
        return $this->hasMany(SummonerMatchFrame::class, 'summoner_match_id', 'id')->orderBy('current_timestamp');
    }

    public function events(): HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class, 'summoner_match_id', 'id')->orderBy('current_timestamp');
    }

    public function item_events(): HasMany
    {
        return $this->events()->whereIn('summoner_match_frame_events.type', [
            FrameEventType::ITEM_PURCHASED,
            FrameEventType::ITEM_SOLD,
        ]);
    }

    public function level_up_skill_events(): HasMany
    {

        return $this->events()->where('summoner_match_frame_events.type', FrameEventType::SKILL_LEVEL_UP);
    }

    public function kills_events(): HasMany
    {
        return $this->events()->where('summoner_match_frame_events.type', FrameEventType::CHAMPION_KILL);
    }

    public function death_events(): HasMany
    {
        return $this->hasMany(SummonerMatchFrameEvent::class, 'summoner_match_victim_id', 'id')
            ->where('summoner_match_frame_events.type', FrameEventType::CHAMPION_KILL);
    }

    public function has_detail(): bool
    {
        return $this->frames()->count() > 0;
    }
}
