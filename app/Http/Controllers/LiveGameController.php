<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerHelper;
use App\Helpers\FilterHelper;
use App\Models\Champion;
use App\Models\Map;
use App\Models\Queue;
use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LiveGameController extends Controller
{


    public function index(Request $request, Summoner $summoner)
    {
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        $lobby_search = $request->validate([
            'lobby_search' => 'nullable|string',
        ])['lobby_search'] ?? null;
        $fake_live_game = null;
        $live_game = null;
        if (!$lobby_search) {
            $live_game = $summoner->getLiveGame();
            if ($live_game) {
                $live_game['map'] = Map::find(intval($live_game['mapId']));
                $live_game['queue'] = Queue::find(intval($live_game['gameQueueConfigId']));
                $seconds = Carbon::createFromTimestamp($live_game['gameStartTime'] / 1000)->diffInSeconds(now());
                $live_game['duration'] = gmdate('H:i:s', $seconds);
                $match_ids = $summoner->summonerMatches()->pluck('match_id');
                $encounter_counts = $summoner->getEncountersCountQuery($match_ids)->pluck('encounter_count', 'summoner_id');
                foreach ($live_game['participants'] as $key => $participant) {
                    $participant_summoner = Summoner::updateOrCreateSummonerByName($participant['summonerName']);
                    $live_game['participants'][$key]['summoner'] = $participant_summoner;
                    $live_game['participants'][$key]['champion'] = Champion::whereId($participant['championId'])->first();
                    $live_game['participants'][$key]['encounter_count'] = $encounter_counts[$participant_summoner->id] ?? 0;
                }
            }
        } else {
            $match_ids = $summoner->summonerMatches()->pluck('match_id');
            $encounter_counts = $summoner->getEncountersCountQuery($match_ids)->pluck('encounter_count', 'summoner_id');
            $fake_live_game = $summoner->getLiveGameFromLobbySearch($lobby_search, $encounter_counts);
        }

        return Inertia::render('Summoner/LiveGame', [
            'live_game' => $live_game,
            'fake_live_game' => $fake_live_game,
        ]);

    }
}
