<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePlayerData;
use App\Transformers\GameTransformer;
use Auth;

class GamesController extends Controller
{
    public function index()
    {
    	$game = Game::first();
    	$players = GamePlayerData::where('game_id', '=', $game->id)->get();
    	$game->gamePlayers = $players;
    	return $this->response->item($game, new GameTransformer());
    }
}
