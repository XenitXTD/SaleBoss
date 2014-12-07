<?php

class TaskTableSeeder extends Seeder {

	/**
	 * Run the Seeding
	 *
	 * @return void
	 */
	public function run()
	{

		$tasks = [
            [
                'creator_id' =>  6,
                'for_id' => 7,
                'priority'  => 1,
                'status' => 1,
                'description' => 'First Task',
                'category' => 1
            ]
		];

		DB::table('tasks')->insert($tasks);
	}
}