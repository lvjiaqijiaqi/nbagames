<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerCareerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_career_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('player_id')->default(0);
            $table->float('PTS')->default(0);
            $table->float('REB')->default(0);
            $table->float('AST')->default(0);
            $table->float('STL')->default(0);
            $table->float('BLK')->default(0);
            $table->float('TO')->default(0);
            $table->integer('season_year')->default(0);
            $table->integer('season_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('player_career_data');
    }
}
