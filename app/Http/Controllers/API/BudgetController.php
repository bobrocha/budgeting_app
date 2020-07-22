<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Budget;

class BudgetController extends Controller
{
	public function store(Request $request) {
		$validator = Validator::make($request->all(), [
			'user_id'  => 'required',
			'month'    => 'required',
			'amount'   => 'required',
		]);

		if($validator->fails()) {
			$errors = [];

			foreach($validator->errors()->toArray() as $error) {
				array_push($errors, array_pop($error));
			}

			return response()->json([
				'errors' => $errors
			]);
		}

		extract($request->all());

		$budget = new Budget;

		$budget->user_id    = $user_id;
		$budget->start_date = $start_date;
		$budget->end_date   = $end_date;
		$budget->amount     = $amount;

		$budget->save();

		return response()->json($budget);

	}
}
