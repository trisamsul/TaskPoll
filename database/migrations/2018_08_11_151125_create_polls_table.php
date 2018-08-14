<!-- 

    Migration Table: Polls
    Table to save Polls Data

-->

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->increments('id');   // Poll Id
            $table->integer('user_id'); // User Id (Poll Owner Id)
            $table->string('title');    // Poll Title
            $table->integer('status');  // Poll status: 1 for open, 0 for closed
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls');
    }
}
