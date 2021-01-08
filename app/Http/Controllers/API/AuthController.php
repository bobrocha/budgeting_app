<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function login(Request $request)
	{

		$request->validate([
			'username' => 'required',
			'password' => 'required',
		]);


		$http = new GuzzleHttp\Client;

		try {
			$response = $http->post('http://localhost/budgeting_app/public/oauth/token', [
				'form_params' => [
					'grant_type'    => 'password',
					'client_id'     => '2',
					'client_secret' => 'MM30A0itVSjGxivJmV4OlzSFh1J00OMq01O1PScY',
					'username'      => $request->username,
					'password'      => $request->password,
					'scope'         => '',
				],
			]);

			return json_decode((string) $response->getBody(), true);

		} catch (ClientException $e) {
			if($e->getCode() === 400 || $e->getCode() === 401) {
				return response()->json([
					'code'    => $e->getCode(),
					'message' => 'Invalid Request. Please enter in correct email and password.',
					'error'   => true,
				]);
			}
		}
	}

	public function create(Request $request) {
		$user = new User();

		$user->name     = $request->name;
		$user->email    = $request->email;
		$user->password = Hash::make($request->password);

		$user->save();

		return response()->json($user);
	}
}
