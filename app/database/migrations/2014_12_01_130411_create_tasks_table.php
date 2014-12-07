<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks',function(Blueprint $t){
				$t->increments('id');
				$t->integer('creator_id')->unsigned()->nullable();
				$t->integer('for_id')->unsigned()->nullable();
				$t->integer('priority')->default(0);
				$t->integer('status')->default(0);
				$t->text('description');
				$t->integer('category')->unsigned()->nullable();
				$t->timestamp('todo_at')->nullable();
				$t->timestamps();

				$t->foreign('creator_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('set null');

				$t->foreign('for_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('set null');

				$t->foreign('category')
				  ->references('id')
				  ->on('tasks_categories')
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
		Schema::drop('tasks',function($t){
				$t->dropForeign('tasks_creator_id_foreign');
				$t->dropForeign('tasks_for_id_foreign');
				$t->dropForeign('tasks_category_foreign');
			});
	}

}
