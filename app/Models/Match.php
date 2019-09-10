<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Match extends Model
{
    protected $fillable = [
        'type', 'mid','left_id', 'left_goal','left_name', 'right_id','right_name', 'start_time','end_time','match_date'
    ];
}
