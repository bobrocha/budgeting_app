<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
	public function index(User $user) {
		return response()->json(
			$user::all()
		);
	}

	public function update(Request $request, User $user) {
		$validator = Validator::make($request->all(), [
			'name'     => 'filled|required',
			'password' => 'filled|required',
		]);

		if($validator->fails()) {
			$errors = [];

			foreach($validator->errors()->toArray() as $error) {
				array_push($errors, array_pop($error));
			}

			return response()->json([
				'errors' => $errors,
			]);
		}

		$user->name     = $request->name;
		$user->password = Hash::make($request->password);

		$user->save();

		return response()->json([
			'name' => $user->name,
		]);
	}
}
