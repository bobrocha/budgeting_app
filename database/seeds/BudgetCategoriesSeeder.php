<?php

use Illuminate\Database\Seeder;

class BudgetCategoriesSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$path = base_path() . '/database/BudgetCategories.json';
		$file = File::get($path);
		$data = json_decode($file, true);

		DB::table('budget_categories')->insert($data);
	}
}
