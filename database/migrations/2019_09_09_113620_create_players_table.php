<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('player_id')->default(0);
            $table->string('en_name')->default('');
            $table->string('cn_name')->default('');
            $table->integer('team_id')->default(0);
            $table->string('position')->default('');
            $table->integer('position_type')->default(0);
            $table->string('icon')->default('');
            $table->string('birth')->default('');
            $table->string('height')->default('');
            $table->string('weight')->default('');
            $table->string('wage')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
