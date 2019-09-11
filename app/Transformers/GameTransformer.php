<?php

namespace App\Transformers;

use App\Models\Game;
use League\Fractal\TransformerAbstract;
use App\Transformers\GamePlayerDataTransformer;
use App\Transformers\PlayTransformer;

class GameTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'gamePlayers' , 'play'
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
        return $this->item($game->play, new PlayTransformer());
    }
}