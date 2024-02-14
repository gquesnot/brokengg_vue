<?php

namespace App\Helpers;

use App\Http\Integrations\LolPro\LolProConnector;
use App\Http\Integrations\LolPro\Requests\SummonerProListRequest;
use App\Http\Integrations\LolPro\Requests\SummonerProRequest;
use App\Models\ProPlayer;
use App\Models\ProPlayerName;
use Illuminate\Console\OutputStyle;
use Illuminate\Support\Arr;

class UpdateProPlayerHelper
{
    public static function updateProPlayer(?OutputStyle $output = null): void
    {
        ProPlayer::query()->delete();
        ProPlayerName::query()->delete();
        $bar = $output?->createProgressBar();
        $connector = new LolProConnector();
        $paginator = $connector->paginate(new SummonerProListRequest());
        $collection = $paginator->collect();
        $collection_count = $collection->count();
        $bar?->setMaxSteps($collection_count);
        $output?->info("Found {$collection_count} pro players");
        $pro_players_array = $collection->map(function ($item) use ($bar) {
            $bar?->advance();

            return [
                'slug' => Arr::get($item, 'slug'),
                'team_name' => Arr::get($item, 'team.name'),
                'name' => Arr::get($item, 'name'),
            ];
        });
        ProPlayer::upsert($pro_players_array->toArray(), ['slug'], ['team_name', 'slug']);
        $bar?->finish();
        $output?->info("Updating {$collection_count} pro players");
        $bar?->setProgress(0);
        $pro_players_array = ProPlayer::all();
        $pool = $connector->pool(
            $pro_players_array->mapWithKeys(function ($item) {
                return [strval($item->id) => new SummonerProRequest($item->slug)];
            }),
            20,
        );
        $pro_players_names_to_insert = [];
        $pool->withResponseHandler(function ($response, int $pro_player_id) use (&$pro_players_names_to_insert, $bar) {
            $profile = $response->json();
            $accounts = Arr::get($profile, 'league_player.accounts', []);
            $summoner_names = Arr::pluck($accounts, 'summoner_name');
            $pro_players_names_to_insert = array_merge(collect($summoner_names)->map(function ($summoner_name) use ($pro_player_id) {
                return [
                    'pro_player_id' => $pro_player_id,
                    'summoner_name' => $summoner_name,
                ];
            })->toArray(), $pro_players_names_to_insert);

            $bar?->advance();
        });
        $promise = $pool->send();
        $promise->wait();
        collect($pro_players_names_to_insert)->chunk(500)->each(function ($chunk) {
            ProPlayerName::upsert($chunk->toArray(), ['summoner_name'], ['summoner_name', 'pro_player_id']);
        });
        $bar?->finish();
    }
}
