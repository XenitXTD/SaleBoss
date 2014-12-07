<?php

class TaskMessagesTableSeeder extends Seeder {

	/**
	 * Run the Seeding
	 *
	 * @return void
	 */
	public function run()
	{

		$tasks = [
            [
                'task_id' => 1,
                'creator_id' =>  6,
                'message' => 'First Message'
            ]
		];

		DB::table('tasks_messages')->insert($tasks);
	}
}