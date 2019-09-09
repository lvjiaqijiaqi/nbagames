<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
  public function addAll(Array $data)
  {
      $rs = DB::table($this->getTable())->insert($data);
      return $rs;
  }
}
