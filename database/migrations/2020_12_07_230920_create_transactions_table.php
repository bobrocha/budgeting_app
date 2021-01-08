<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function (Blueprint $table) {
			$table->bigIncrements('id');
			$table->date('date');
			$table->string('description', 350);
			$table->bigInteger('category_id')->unsigned();
			$table->decimal('amount', 15, 2);
			$table->bigInteger('user_id')->unsigned();
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
		Schema::dropIfExists('transactions');
	}
}
