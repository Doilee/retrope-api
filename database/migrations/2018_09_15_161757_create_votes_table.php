<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id')->unsigned();
            $table->unsignedInteger('action_id')->unsigned();
            $table->tinyInteger('value')->default(1);
            $table->timestamp('created_at');

            $table->foreign('player_id')->references('id')->on('players')
                ->onDelete('cascade');
            $table->foreign('action_id')->references('id')->on('actions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}
