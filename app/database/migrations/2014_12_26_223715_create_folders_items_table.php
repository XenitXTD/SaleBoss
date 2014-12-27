<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folders_items',function(Blueprint $t){
				$t->increments('id');
				$t->integer('creator_id')->unsigned()->nullable();
				$t->string('name');
				$t->text('for_id');
				$t->text('description');
				$t->timestamps();

				$t->foreign('creator_id')
				  ->references('id')
				  ->on('users')
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
		Schema::drop('folders_items');
	}

}
