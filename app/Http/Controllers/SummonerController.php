<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateSummonerJob;
use App\Models\Summoner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        // check if summoner exists
        if (! Summoner::where('name', 'like', "%{$summoner_name}%")->exists()) {
            $summoner = Summoner::updateOrCreateSummonerByName($summoner_name);
            if (! $summoner) {
                return redirect()->back()->withErrors(['summoner_name' => 'Summoner not found']);
            }
        } else {
            $summoner = Summoner::where('name', 'like', "%{$summoner_name}%")->first();
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
