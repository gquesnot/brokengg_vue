<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Champion;
use App\Models\Map;
use App\Models\ProPlayerName;
use App\Models\Queue;
use App\Models\Summoner;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

class LiveGameController extends Controller
{
    public function index(Request $request, int $summoner_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        [$filters, $filters_cpy] = FilterHelper::parseFilters($request);
        $lobby_search = $request->validate([
            'lobby_search' => 'nullable|string',
        ])['lobby_search'] ?? null;
        $fake_live_game = null;
        $live_game = null;
        $errors = [];

        try {
            $live_game = $this->getLiveGameData($summoner);
        } catch (NotFoundException|ForbiddenException $e) {

        } catch (RateLimitReachedException $e) {
            $errors = ['api' => 'Rate limit reached, please try again later'];
        }

        if ($lobby_search && !$live_game) {
            $match_ids = $summoner->summoner_matches()->pluck('match_id');
            $encounter_counts = $summoner->get_encounters_count_query($match_ids)->pluck('encounter_count', 'summoner_id');
            $fake_live_game = $summoner->get_live_game_from_lobby_search($lobby_search, $encounter_counts);
        }

        return Inertia::render('Summoner/LiveGame', [
            'live_game' => $live_game,
            'fake_live_game' => $fake_live_game,
        ])->with('errors', $errors);

    }

    /**
     * @throws RateLimitReachedException
     * @throws ForbiddenException
     * @throws NotFoundException
     */
    private function getLiveGameData(Summoner $summoner)
    {
        $live_game = $summoner->getLiveGame();
        $live_game['map'] = Map::find(intval($live_game['mapId']));
        $live_game['queue'] = Queue::find(intval($live_game['gameQueueConfigId']));
        $seconds = Carbon::createFromTimestamp($live_game['gameStartTime'] / 1000)->diffInSeconds(now());
        $live_game['duration'] = gmdate('H:i:s', $seconds);
        $match_ids = $summoner->summoner_matches()->pluck('match_id');
        $encounter_counts = $summoner->get_encounters_count_query($match_ids)->pluck('encounter_count', 'summoner_id');
        foreach ($live_game['participants'] as $key => $participant) {
            $participant_summoner = Summoner::whereName($participant['summonerName'])->first();
            if ($participant_summoner) {
                $participant_summoner->load('pro_player');
            } else {
                $pro_player = ProPlayerName::whereName($participant['summonerName'])->with('proPlayer')->first();

                $participant_summoner = [
                    'id' => null,
                    'name' => $participant['summonerName'],
                    'profile_icon_id' => $participant['profileIconId'],
                    'pro_player' => $pro_player?->proPlayer,
                ];
            }

            $live_game['participants'][$key]['summoner'] = $participant_summoner;
            $live_game['participants'][$key]['champion'] = Champion::whereId($participant['championId'])->first();
            if ($participant_summoner['id']) {
                $live_game['participants'][$key]['encounter_count'] = $encounter_counts[$participant_summoner['id']] ?? 0;
            }
        }

        return $live_game;
    }
}
