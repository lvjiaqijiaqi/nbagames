<?php

namespace App\Transformers;

use App\Models\GamePlayerData;
use League\Fractal\TransformerAbstract;

class GamePlayerDataTransformer extends TransformerAbstract
{
    public function transform(GamePlayerData $gamePlayerData)
    {
        return [
            'player_id' => $gamePlayerData->player_id,
            'player_name' => $gamePlayerData->player_cn_name,
            'position_type' => $gamePlayerData->position_type,
            'PTS' => $gamePlayerData->PTS,
            'REB' => $gamePlayerData->REB,
            'AST' => $gamePlayerData->AST,
            'STL' => $gamePlayerData->STL,
            'BLK' => $gamePlayerData->BLK,
            'TO' => $gamePlayerData->TO,
            'DPTS' => $gamePlayerData->DPTS,
            'DREB' => $gamePlayerData->DREB,
            'DAST' => $gamePlayerData->DAST,
            'DSTL' => $gamePlayerData->DSTL,
            'DBLK' => $gamePlayerData->DBLK,
            'DTO' => $gamePlayerData->DTO,
        ];
	}
}