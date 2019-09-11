<?php

namespace App\Transformers;

use App\Models\Game;
use League\Fractal\TransformerAbstract;
use App\Transformers\GamePlayerDataTransformer;

class GameTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'gamePlayers'
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
}