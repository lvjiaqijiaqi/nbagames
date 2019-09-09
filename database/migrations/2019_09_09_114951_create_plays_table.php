<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plays', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('game_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->integer('C')->default(0);
            $table->integer('PF')->default(0);
            $table->integer('SF')->default(0);
            $table->integer('SG')->default(0);
            $table->integer('PG')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plays');
    }
}
