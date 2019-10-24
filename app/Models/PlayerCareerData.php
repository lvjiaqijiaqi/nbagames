<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerCareerData extends Model
{
    protected $fillable = [
        'player_id', 'season_year','season_type', 'PTS','REB', 'AST','BLK', 'TO','STL',"match_count"
    ];
}
