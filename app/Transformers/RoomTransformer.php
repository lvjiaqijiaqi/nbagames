<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\Room;

class RoomTransformer extends TransformerAbstract
{
    public function transform(Room $room)
    {
        return [
            'id' => $room->id,
            'PTS' => $room->PTS,
            'REB' => $room->REB,
            'AST' => $room->AST,
            'STL' => $room->STL,
            'BLK' => $room->BLK,
            'TO' => $room->TO,
            'total' => $room->total,
        ];
	}
}