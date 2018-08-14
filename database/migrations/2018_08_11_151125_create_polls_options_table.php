<!-- 

    Migration Table: Polls Options
    Table to save Polls Options

-->

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls_options', function (Blueprint $table) {
            $table->increments('id');               // Option Id
            $table->integer('poll_id');             // Poll Id
            $table->string('text');                 // Option text
            $table->integer('voted')->default(0);   // Option number of votes result
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('polls_options');
    }
}
