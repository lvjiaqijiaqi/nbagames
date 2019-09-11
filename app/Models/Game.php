<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GamePlayerData;
use App\Models\Room;
use App\Models\Play;

class Game extends Model
{
    protected $fillable = ['game_date','match_num','status','PTS','REB','AST','STL','BLK','TO'];
    public $gamePlayers;
    public $play;
    public $room;
    public function checkValid($players , Room $room){
    	$validTotal =  $this->PTS * $room->PTS +
    					$this->REB * $room->REB +
    					$this->AST * $room->AST +
    					$this->STL * $room->STL +
    					$this->BLK * $room->BLK +
    					$this->TO * $room->TO;
    	$inputTotal = $players['PTS'] * $room->PTS +
    					$players['REB'] * $room->REB +
    					$players['AST'] * $room->AST +
    					$players['STL'] * $room->STL +
    					$players['BLK'] * $room->BLK +
    					$players['TO'] * $room->TO;
    	return $validTotal * $room->right > $inputTotal;
    } 
}
