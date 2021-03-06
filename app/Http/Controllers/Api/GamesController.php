<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\GamePlayerData;
use App\Transformers\GameTransformer;
use App\Models\Play;
use App\Models\Room;
use Auth;

class GamesController extends Controller
{
    public function index()
    {
    	$game = Game::where('status', '!=', '3')->orderBy('id','desc')->first();
      if ($game) {
          $players = GamePlayerData::where('game_id', '=', $game->id)->get();
          $game->gamePlayers = $players;
          $game->room = Room::first();
          $game->play = Play::where(array('game_id' => $game->id , 'room_id' => $game->room->id , 'user_id' => $this->user()->id))->first();
          return $this->response->item($game, new GameTransformer());
      }else{
          return $this->response->error('没有找到游戏', 404);
      }
    }
}
