<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataRatio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->float('PTS')->default(0);
            $table->float('REB')->default(0);
            $table->float('AST')->default(0);
            $table->float('STL')->default(0);
            $table->float('BLK')->default(0);
            $table->float('TO')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn('PTS');
            $table->dropColumn('REB');
            $table->dropColumn('AST');
            $table->dropColumn('STL');
            $table->dropColumn('BLK');
            $table->dropColumn('TO');
        });
    }
}
