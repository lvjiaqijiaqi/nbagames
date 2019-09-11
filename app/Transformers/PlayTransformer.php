<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Play;

class PlayTransformer extends TransformerAbstract
{
    public function transform(Play $play)
    {
        return [
            'id' => $play->id,
            'game_id' => $play->game_id,
            'room_id' => $play->room_id,
            'C' => $play->C,
            'PF' => $play->PF,
            'SF' => $play->SF,
            'SG' => $play->SG,
            'PG' => $play->PG
        ];
	}
}