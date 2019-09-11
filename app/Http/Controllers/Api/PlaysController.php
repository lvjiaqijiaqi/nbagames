<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\Api\PlayRequest;
use App\Models\GamePlayerData;
use App\Models\Enum\PlayerPositionEnum;
use App\Models\Play;
use App\Models\Room;
use App\Models\Game;
use App\Transformers\GamePlayerDataTransformer;

class PlaysController extends Controller
{
    public function store(PlayRequest $request, $gameId){
    	$game = Game::where('id', $gameId)->first();
    	if($game->game_status == 0){
    		$players = array();
    		$C_player = GamePlayerData::where(['player_id' => $request->C])->first();
	    	$PF_player = GamePlayerData::where(['player_id' => $request->PF])->first();
	    	$SF_player = GamePlayerData::where(['player_id' => $request->SF])->first();
	    	$SG_player = GamePlayerData::where(['player_id' => $request->SG])->first();
	    	$PG_player = GamePlayerData::where(['player_id' => $request->PG])->first();
	    	isset($C_player->player_id) && $players[] = $C_player;
	    	isset($PF_player->player_id) && $players[] = $PF_player;
	    	isset($SF_player->player_id) && $players[] = $SF_player;
	    	isset($SG_player->player_id) && $players[] = $SG_player;
	    	isset($PG_player->player_id) && $players[] = $PG_player;
	    	if (count($players) != 5) {
	    		return $this->response->error('服务器有点问题', 404);
	    	}
	    	if (($C_player->position_type & PlayerPositionEnum::PLAYER_POSITION_C) &&
	    		($PF_player->position_type & PlayerPositionEnum::PLAYER_POSITION_PF) &&
	    		($SF_player->position_type & PlayerPositionEnum::PLAYER_POSITION_SF) &&
	    		($SG_player->position_type & PlayerPositionEnum::PLAYER_POSITION_SG) &&
	    		($PG_player->position_type & PlayerPositionEnum::PLAYER_POSITION_PG)) {
	    		$total = ['PTS' => 0 , 'REB' => 0 ,'AST' => 0 ,'STL' => 0 ,'BLK' => 0 ,'TO' => 0 ];
	    		foreach ($players as $player) {
	    			$total['PTS'] += $player->PTS;
	    			$total['REB'] += $player->REB;
	    			$total['AST'] += $player->AST;
	    			$total['STL'] += $player->STL;
	    			$total['BLK'] += $player->BLK;
	    			$total['TO'] += $player->TO;
	    		}
	    		$total['PTS'] = $total['PTS'] / count($players);
	    		$total['REB'] = $total['REB'] / count($players);
	    		$total['AST'] = $total['AST'] / count($players);
	    		$total['STL'] = $total['STL'] / count($players);
	    		$total['BLK'] = $total['BLK'] / count($players);
	    		$total['TO'] = $total['TO'] / count($players);
	    		$room = Room::first();
	    		if ($game->checkValid($total,$room)) {
	    			$input = array(
	    							'game_id' => $game->id,
	    							'user_id' => $this->user()->id,
	    							'room_id' => $room->id,
	    							'C' => $C_player->player_id , 
	    						    'PF' => $PF_player->player_id,
	    							'SF' => $SF_player->player_id,
	    							'SG' => $SG_player->player_id,
	    							'PG' => $PG_player->player_id);
	    			Play::updateOrCreate(array('game_id' => $game->id , 'room_id' => $room->id , 'user_id' => $this->user()->id) ,$input);
	    		}else{
	    			return $this->response->error('阵容超过工资帽', 10002);
	    		}
	    	}
    	}else{
    		return $this->response->error('比赛已经开始，不能再选择阵容', 10001);
    	}
    } 
}
