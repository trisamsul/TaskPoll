<!-- 

    Migration Table: Users    
    Table to save Users Data

-->

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');               // User Id
            $table->string('username')->unique();   // Username
            $table->string('email')->unique();      // Email
            $table->string('password');             // Password
            $table->integer('category');            // Category: 1 for Administrator, 0 for Basic User
        });

        // Inser dummy data for Administrator and Basic User, one data for each
        DB::table('users')->insert([
            [
                'username' => 'admin', 
                'email' => 'admin@dummy.com', 
                'password' => '$2y$12$CyPf1u8Sa79PNEzYa8FQY.Q9DWXOUizx9zu0dDbQjFxKJZfQrgAN6', 
                'category' => 1
            ],
            [
                'username' => 'basic', 
                'email' => 'basic@dummy.com', 
                'password' => '$2y$12$G2cyAs6dGnK1PMXCuwrHqOyBfnqK.GuMEf.w4eGhFfBayqaVM7Li6', 
                'category' => 0
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
