<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('upload', function(Blueprint $table) {
				$table->increments('id');
				$table->string('name');
				$table->string('path');
				$table->integer('for_id')->unsigned()->nullable();
				$table->string('for_type')->unsigned()->nullable();
				$table->timestamps();
			});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('upload');
	}

}
