<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProPlayerName
 *
 * @property int $id
 * @property string $summoner_name
 * @property int $pro_player_id
 * @property-read \App\Models\ProPlayer $proPlayer
 * @property-read \App\Models\Summoner|null $summoner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName whereProPlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProPlayerName whereSummonerName($value)
 *
 * @mixin \Eloquent
 */
class ProPlayerName extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'summoner_name',
        'pro_player_id',
    ];

    public function proPlayer()
    {
        return $this->belongsTo(ProPlayer::class);
    }

    public function summoner()
    {
        return $this->hasOne(Summoner::class, 'name', 'summoner_name');
    }
}
