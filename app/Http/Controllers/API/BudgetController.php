<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BudgetController extends Controller
{
	public function index() {
		$userID  = Auth::user()->id;
		$year    = date('Y');
		$query   = '
			SELECT
				b.start_date,
				b.end_date,
				b.id,
				b.amount,
				c.title title,
				b.year
			FROM
				budgets b
			JOIN
				budget_categories c ON b.category_id = c.id
			WHERE
				b.user_id = :user_id AND
				b.year    = :year
		';

		$results = collect(
			DB::select($query, [
				'user_id' => $userID,
				'year'    => $year,
			])
		)
		->map(function($result) {
			return [
				'id'     => $result->id,
				'month'  => $this->getMonthFomDate($result->start_date),
				'amount' => $result->amount,
				'year'   => $this->getYearFromDate($result->start_date),
				'title'  => $result->title,
			];
		});

		return response()->json(
			$results
		);
	}

	public function store(Request $request) {
		$userID    = Auth::user()->id;
		$validator = Validator::make($request->all(), [
			'startDate' => 'required|filled',
			'endDate'   => 'required|filled',
			'amount'    => 'required|filled',
			'category'  => 'required|filled',
			'year'      => 'required|filled',
		]);

		if($validator->fails()) {
			$errors = [];

			foreach($validator->errors()->toArray() as $error) {
				array_push($errors, reset($error));
			}

			return response()->json([
				'errors' => $errors
			]);
		}

		extract($request->all());

		if($this->budgetExists($startDate, $endDate, $userID, $category)) {
			return response()->json([
				'errors' => 'A budget for this period and category already exists. Please try a different one.',
			]);
		}

		$budget              = new Budget;
		$budget->user_id     = $userID;
		$budget->start_date  = $startDate;
		$budget->end_date    = $endDate;
		$budget->amount      = $amount;
		$budget->category_id = $category;
		$budget->year        = $year;

		$budget->save();
		$budget->reFresh();

		return response()->json([
				'id'       => $budget->id,
				'month'    => $this->getMonthFomDate($startDate),
				'amount'   => $budget->amount,
				'year'     => $this->getYearFromDate($startDate),
				'category' => $category,
			]
		);
	}

	public function update(Request $request, Budget $budget) {
		$validator = Validator::make($request->all(), [
			'id'     => 'required|filled',
			'amount' => 'required|filled',
		]);

		if($validator->fails()) {
			return response()->json([
				'errors' => $validator->errors()->all(),
			]);
		}

		extract($request->all());

		$budget->amount = $amount;

		$budget->save();
		$budget->reFresh();

		return response()->json([
				'id'     => $budget->id,
				'amount' => $budget->amount,
		]);
	}

	public function destroy(Budget $budget) {
			if($budget->delete()) {
				return response()->json([
					'success' => true,
				]);
			}
	}

	private function getMonthFomDate($date) {
		return date('F', strtotime($date));
	}

	private function getYearFromDate($date) {
		return date('Y', strtotime($date));
	}

	private function budgetExists($startDate, $endDate, $userID, $categoryId) {
		$year  = date('Y');
		$query = '
			SELECT CASE WHEN EXISTS (
				SELECT
					1
				FROM
					budgets
				WHERE
					start_date  = :start_date AND
					end_date    = :end_date AND
					user_id     = :user_id AND
					category_id = :category_id AND
					year        = :year
			) THEN 1 ELSE 0 END as \'exists\';
		';

		$params = [
			'start_date'  => $startDate,
			'end_date'    => $endDate,
			'user_id'     => $userID,
			'category_id' => $categoryId,
			'year'        => $year,
		];

		return !!collect(DB::select($query, $params))->first()->exists;
	}
}
