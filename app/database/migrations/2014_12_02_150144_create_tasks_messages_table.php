<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksMessagesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks_messages',function(Blueprint $t){
				$t->increments('id');
				$t->integer('task_id')->unsigned()->nullable();
				$t->integer('creator_id')->unsigned()->nullable();
				$t->integer('read')->default(0);
				$t->text('message');
				$t->timestamps();

				$t->foreign('creator_id')
				  ->references('id')
				  ->on('users')
				  ->onDelete('set null');

				$t->foreign('task_id')
				  ->references('id')
				  ->on('tasks')
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
				$t->dropForeign('tasks_task_id_foreign');
			});
	}

}
