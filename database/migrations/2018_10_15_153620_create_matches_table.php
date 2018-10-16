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
            $table->increments('id');
            $table->integer("week");
            $table->integer("team_1")->unsigned();
            $table->integer("team_1_goal");
            $table->integer("team_2")->unsigned();
            $table->integer("team_2_goal");

            $table->foreign('team_1')->references('id')->on('teams')->onDelete('cascade');;
            $table->foreign('team_2')->references('id')->on('teams')->onDelete('cascade');;
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
