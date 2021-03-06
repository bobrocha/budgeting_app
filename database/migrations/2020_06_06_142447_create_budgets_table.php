<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('budgets', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->unsigned();
			$table->date('start_date');
			$table->date('end_date');
			$table->decimal('amount', 15, 2);
			$table->bigInteger('category_id')->unsigned();
			$table->smallInteger('year');
			$table->timestamps();

			// Users foreing key constraint
			$table
				->foreign('user_id')
				->references('id')
				->on('users')
				->onDelete('cascade');

			// Categories foregin key constraint
			$table
				->foreign('category_id')
				->references('id')
				->on('budget_categories');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('budgets');
	}
}
