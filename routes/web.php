<?php
// Route all routes to entry point
Route::get('/{params?}', function($params = null) {
	return view('app');
})->where('params', '.*')->name('home');
