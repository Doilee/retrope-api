<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('host_id');
            $table->integer('name');
            $table->integer('timer')->default(180);
            $table->boolean('is_public')->default(true);
            $table->string('invitation_code');
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable();
            $table->softDeletes();

            $table->foreign('host_id')->references('id')->on('users');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("sessions");
    }
}