<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->group(function() {
	Route::get('/user', function(Request $request) {
		return $request->user();
	});

	// Users
	Route::get('/users', 'API\UserController@index');
	Route::post('/users/update/{user}', 'API\UserController@update');

	// Budgets
	Route::get('/budgets/{budget}', 'API\BudgetController@show');
	Route::get('/budgets', 'API\BudgetController@index');
	Route::post('/budgets/store/', 'API\BudgetController@store');
	Route::post('/budgets/destroy/{budget}', 'API\BudgetController@destroy');
	Route::post('/budgets/update/{budget}', 'API\BudgetController@update');

	// Budget categories
	Route::get('/budget_categories', 'API\BudgetCategoriesController@index');

	// Expenses
	Route::get('/transactions', 'API\ExpensesController@index');
	Route::get('/transactions/years', 'API\ExpensesController@years');
	Route::post('/transactions', 'API\ExpensesController@store');
	Route::patch('/transactions/{transaction}', 'API\ExpensesController@update');
	Route::delete('/transactions/{transaction}', 'API\ExpensesController@destroy');
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@create');
