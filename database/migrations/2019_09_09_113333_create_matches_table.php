<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matches', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('game_id')->default(0);
            $table->integer('type')->default(0);
            $table->string('mid')->default('');
            $table->integer('left_id')->default(0);
            $table->integer('left_goal')->default(0);
            $table->string('left_name')->default('');
            $table->integer('right_id')->default(0);
            $table->integer('right_goal')->default(0);
            $table->string('right_name')->default('');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->date('match_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches');
    }
}
