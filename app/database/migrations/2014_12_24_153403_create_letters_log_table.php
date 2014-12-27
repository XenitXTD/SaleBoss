<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letters_log',function(Blueprint $t){
				$t->increments('id');
				$t->integer('letter_id')->unsigned()->nullable();
				$t->integer('creator_id')->unsigned()->nullable();
				$t->integer('path_id')->unsigned()->nullable();
				$t->integer('log_type');
				$t->text('message');
				$t->timestamps();

				$t->foreign('creator_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('set null');

				$t->foreign('path_id')
				  ->references('id')
				  ->on('letters_path')
				  ->onDelete('set null');
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('letters_log');
	}

}
