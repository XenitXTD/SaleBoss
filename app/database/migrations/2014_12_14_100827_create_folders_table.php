<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFoldersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('folders',function(Blueprint $t){
				$t->increments('id');
				$t->integer('parent_id')->unsigned()->nullable();
				$t->string('name');
				$t->integer('creator_id')->unsigned()->nullable();
				$t->string('for_type');
				$t->integer('for_id');
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
		Schema::drop('folders');
	}

}
