<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateSummonerJob;
use App\Models\Summoner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Saloon\Exceptions\Request\Statuses\ForbiddenException;
use Saloon\Exceptions\Request\Statuses\NotFoundException;
use Saloon\RateLimitPlugin\Exceptions\RateLimitReachedException;

class SummonerController extends Controller
{
    public function index()
    {
        return Inertia::render('Summoner/Index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'summoner_name' => 'required|string',
        ]);
        $summoner_name = $validated['summoner_name'];

        $query = Summoner::where('name', 'like', "%{$summoner_name}%");
        // check if summoner exists
        if (!$query->clone()->exists()) {
            try {
                $summoner = Summoner::updateOrCreateSummonerByNameAndTagLine($summoner_name);
            } catch (NotFoundException $e) {
                return redirect()->back()->withErrors(['api' => 'Summoner not found']);
            } catch (RateLimitReachedException $e) {
                return back()->withErrors(['api' => 'Rate limit reached, please try again later']);
            } catch (ForbiddenException $e) {
                return back()->withErrors(['api' => 'API key is invalid']);
            }
        } else {
            $summoner = $query->clone()->first();
        }

        return to_route('summoner.matches', ['summoner_id' => $summoner->id]);
    }

    public function update(Request $request, int $summoner_id)
    {
        try {
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }
        UpdateSummonerJob::dispatch($summoner);

        return redirect()->back();
    }
}
