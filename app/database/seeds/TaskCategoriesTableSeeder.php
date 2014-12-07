<?php

class TaskCategoriesTableSeeder extends Seeder {

	/**
	 * Run the Seeding
	 *
	 * @return void
	 */
	public function run()
	{
        $tasksCategory = [
            [
                'name' =>  'First Category Name',
                'text' =>   'First Category Text'
            ]
        ];

        DB::table('tasks_categories')->insert($tasksCategory);
	}
}