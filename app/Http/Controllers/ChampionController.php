<?php

namespace App\Http\Controllers;

use App\Helpers\FilterHelper;
use App\Models\Champion;
use App\Models\Summoner;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChampionController extends Controller
{
    public function index(Request $request, int $summoner_id, int $champion_id)
    {
        try {
            $champion = Champion::findOrFail($champion_id);
            $summoner = Summoner::findOrFail($summoner_id);
        } catch (ModelNotFoundException $e) {
            return to_route('home');
        }


        return Inertia::render('Summoner/Champion', [
            'champion' => $champion,
        ]);
    }
}
