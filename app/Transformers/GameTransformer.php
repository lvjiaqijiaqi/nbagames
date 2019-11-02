<?php

namespace App\Transformers;

use App\Models\Game;
use League\Fractal\TransformerAbstract;
use App\Transformers\GamePlayerDataTransformer;
use App\Transformers\PlayTransformer;
use App\Transformers\RoomTransformer;
use App\Models\Room;

class GameTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'gamePlayers' , 'play' , 'room'
    ];

    public function transform(Game $game)
    {
        return [
            'id' => $game->id,
            'game_date' => $game->game_date,
            'match_num' => $game->match_num,
            'status' => $game->status
        ];
    }
    
    public function includeGamePlayers(Game $game)
    {
        return $this->collection($game->gamePlayers, new GamePlayerDataTransformer());
    }

    public function includePlay(Game $game){
        if ($game->play) {
            return $this->item($game->play, new PlayTransformer());
        }
    }

    public function includeRoom(Game $game){
        $validTotal =  $game->PTS * $game->room->PTS +
                        $game->REB * $game->room->REB +
                        $game->AST * $game->room->AST +
                        $game->STL * $game->room->STL +
                        $game->BLK * $game->room->BLK +
                        $game->TO * $game->room->TO;
        $game->room->total = ($validTotal * 5) * $game->room->right;               
        return $this->item($game->room, new RoomTransformer());
    }
}