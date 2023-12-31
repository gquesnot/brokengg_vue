<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/summoner', \App\Http\Controllers\SummonerController::class)->only([
    'store', 'update',
]);

Route::get('/summoner/{summoner_id}/matches', [\App\Http\Controllers\MatchesController::class, 'index'])->name('summoner.matches');
Route::get('/summoner/{summoner_id}/matches/{summoner_match_id}', [\App\Http\Controllers\MatchController::class, 'index'])->name('summoner.match');
Route::get('/summoner/{summoner_id}/encounters', [\App\Http\Controllers\EncountersController::class, 'index'])->name('summoner.encounters');
Route::get('/summoner/{summoner_id}/encounters/{encounter_id}', [\App\Http\Controllers\EncounterController::class, 'index'])->name('summoner.encounter');
Route::get('/summoner/{summoner_id}/champions', [\App\Http\Controllers\ChampionsController::class, 'index'])->name('summoner.champions');
Route::get('/summoner/{summoner_id}/champions/{champion_id}', [\App\Http\Controllers\ChampionController::class, 'index'])->name('summoner.champion');
Route::get('/summoner/{summoner_id}/live-game', [\App\Http\Controllers\LiveGameController::class, 'index'])->name('summoner.live-game');

Route::get('/summoner/{summoner_id}/matches/{summoner_match_id}/fully_loaded', [\App\Http\Controllers\MatchController::class, 'getSummonerMatchLoaded'])->name('summoner.match.loaded');
Route::get('/summoner/{summoner_id}/matches/{summoner_match_id}/detail', [\App\Http\Controllers\MatchController::class, 'getSummonerMatchDetail'])->name('summoner.match.detail');

Route::get('/', [App\Http\Controllers\SummonerController::class, 'index'])->name('home');

Route::get('/sync', function () {
    \App\Jobs\UpdateDragonDataJob::dispatch();
})->name('sync');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
