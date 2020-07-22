<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->group(function() {
	Route::get('/user', function(Request $request) {
		return $request->user();
	});

	Route::get('/users', 'API\UserController@index');
	Route::post('/users/update/{user}', 'API\UserController@update');

	Route::get('/budgets/{budget}', 'API\BudgetController@show');
	Route::post('/budgets/store/', 'API\BudgetController@store');
});

Route::post('login', 'API\AuthController@login');
Route::post('register', 'API\AuthController@create');


// http://localhost/budgeting_app/public/api/oauth/token'
// http://localhost/budgeting_app/public/oauth/token
