<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = [
        'player_id', 'en_name','cn_name', 'team_id','position', 'icon','birth', 'height','weight','wage','position_type'
    ];
}
