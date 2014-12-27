<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersPathTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('letters_path',function(Blueprint $t){
				$t->increments('id');
				$t->integer('letter_id')->unsigned()->nullable();
				$t->integer('start')->unsigned()->nullable();
				$t->integer('destination')->unsigned()->nullable();
				$t->integer('prev_place')->unsigned()->nullable();
				$t->integer('current_place')->unsigned()->nullable();
				$t->integer('next_place')->unsigned()->nullable();
				$t->text('path');
				$t->timestamps();

				$t->foreign('letter_id')
				  ->references('id')
				  ->on('letters')
				  ->onDelete('set null');

				$t->foreign('start')
				  ->references('id')
				  ->on('groups')
				  ->onDelete('set null');

				$t->foreign('destination')
				  ->references('id')
				  ->on('groups')
				  ->onDelete('set null');

				$t->foreign('prev_place')
				  ->references('id')
				  ->on('groups')
				  ->onDelete('set null');

				$t->foreign('current_place')
				  ->references('id')
				  ->on('groups')
				  ->onDelete('set null');

				$t->foreign('next_place')
				  ->references('id')
				  ->on('groups')
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
		Schema::drop('letters_path');
	}

}
