<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExpensesController extends Controller
{
	public function years()
	{
		$userID = Auth::user()->id;
		$query  = '
			SELECT DISTINCT
				year
			FROM
				transactions
			WHERE
				user_id = :user_id
		';

		$results = DB::select($query, ['user_id' => $userID]);
		$years   = [];

		foreach($results as $result) {
			array_push($years, $result->year);
		}
		return response()->json($years);
	}
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		$userID    = Auth::user()->id;
		$validator = Validator::make($request->all(), [
			'year' => 'required|filled',
		]);

		if($validator->fails()) {
			$error = $validator->errors()->get('year');

			return response()->json([
				'error' => reset($error),
			]);
		}

		$year = $request->year;
		$query = '
			SELECT
				DATE_FORMAT(t.date, "%m/%d/%Y") date,
				t.description,
				c.title  category,
				t.amount,
				t.id transactionID,
				c.id categoryID
			FROM
				transactions t
			JOIN
				budget_categories c ON t.category_id = c.id
			WHERE
				t.year    = :year AND
				t.user_id = :user_id
		';

		$rows = DB::select($query, [
			'user_id' => $userID,
			'year'    => $year,
		]);

		$processedResults = [];
		$targetAttributes = ['transactionID', 'categoryID'];

		foreach($rows as $row) {
			$fields     = [];
			$attributes = [];
			$tmpRow     = [];

			foreach($row as $key => $val) {
				if(in_array($key, $targetAttributes)) {
					$attributes[$key] = $val;
				} else {
					$fields[$key] = $val;
				}
			}

			$tmpRow['attributes'] = $attributes;
			$tmpRow['fields']     = $fields;
			$processedResults[]   = $tmpRow;
		}

		return response()->json($processedResults);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		$userID    = Auth::user()->id;
		$validator = Validator::make($request->all(), [
			'date'        => 'required|filled',
			'description' => 'required|filled',
			'category_id' => 'required|filled',
			'amount'      => 'required|filled',
			'year'        => 'required|filled',
		]);

		if($validator->fails()) {
			$errors = [];

			foreach($validator->errors()->toArray() as $error) {
				array_push($errors, reset($error));
			}

			return response()->json([
				'errors' => $errors,
			]);
		}

		$requestVars = $request->only([
			'date',
			'description',
			'category_id',
			'amount',
			'year',
		]);

		$transaction = new Transaction;

		foreach($requestVars as $key => $val) {
			$transaction->$key = $val;
		}

		$transaction->user_id = $userID;

		$transaction->save();
		$transaction->reFresh();

		return response()->json($transaction);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Models\Transaction  $transaction
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Transaction $transaction)
	{
		foreach($request->except('id') as $key => $val) {
			$transaction->$key = $val;
		}

		$transaction->save();
		$transaction->refresh();

		return response()->json($transaction);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\Models\Transaction  $transaction
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Transaction $transaction)
	{
		if($transaction->delete()) {
			return response()->json([
				'success' => true,
			]);
		}
	}
}
