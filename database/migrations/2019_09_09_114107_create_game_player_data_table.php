<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePlayerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_player_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('player_id')->default(0);
            $table->integer('game_id')->default(0);
            $table->integer('position_type')->default(0);
            $table->string('player_cn_name')->default('');
            $table->float('PTS')->default(0);
            $table->float('REB')->default(0);
            $table->float('AST')->default(0);
            $table->float('STL')->default(0);
            $table->float('BLK')->default(0);
            $table->float('TO')->default(0);
            $table->float('DPTS')->default(0);
            $table->float('DREB')->default(0);
            $table->float('DAST')->default(0);
            $table->float('DSTL')->default(0);
            $table->float('DBLK')->default(0);
            $table->float('DTO')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_player_data');
    }
}
