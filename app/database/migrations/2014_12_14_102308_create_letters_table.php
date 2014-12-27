<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letters',function(Blueprint $t){
				$t->increments('id');
				$t->string('subject');
				$t->text('message');
				$t->integer('creator_id')->unsigned()->nullable();
				$t->integer('folder_id')->unsigned()->nullable();
				$t->timestamps();

				$t->foreign('creator_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('set null');

				$t->foreign('folder_id')
				  ->references('id')
				  ->on('folders')
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
		Schema::drop('letters');
	}

}
