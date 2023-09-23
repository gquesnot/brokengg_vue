<?php

namespace App\Http\Middleware;

use App\Models\Summoner;
use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class EnsureBindingMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $summoner_id = $request->route('summoner');
        if ($summoner_id) {
            try {
                $summoner = Summoner::select('id')->findOrFail($summoner_id);
            } catch (ModelNotFoundException $e) {
                return to_route('home');
            }
        }

        return $next($request);
    }
}
