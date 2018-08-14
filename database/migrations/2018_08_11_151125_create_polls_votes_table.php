<!-- 

    Migration Table: Polls Votes
    Table to save votes data for every Poll

-->

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls_votes', function (Blueprint $table) {
            $table->increments('id');       // Vote Id
            $table->integer('poll_id');     // Poll Id
            $table->integer('option_id');   // Option Id
            $table->integer('user_id');     // User Id (User that committed this vote)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls_votes');
    }
}
