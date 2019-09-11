<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Play extends Model
{
	protected $fillable = ['game_id', 'C','PF', 'SF','PG', 'SG' ,'user_id', 'room_id'];
}
