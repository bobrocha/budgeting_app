<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiUserAuthentication extends TestCase
{

	const UNPROCESSABLE_ENTITY_STATUS_CODE = 422;
	const UNAUTHORIZED_STATUS_COCDE        = 401;

	const ERRORS = [
		'message'     => ['message' => 'The given data was invalid.'],
		'validations' => [
			'username' => ['username' => 'The username field is required.'],
			'password' => ['password' => 'The password field is required.'],
		],
	];

	public function testNotAuthenticated() {
		$response = $this->getJson('/api/budgets');

		$response->assertJSON([
			'message' => 'Unauthenticated.',
		]);
	}

	public function testEmptyCredsLogin()
	{
		$response = $this->postJSON('/api/login', []);

		$response
			->assertStatus(self::UNPROCESSABLE_ENTITY_STATUS_CODE)
			->assertJson(self::ERRORS['message'])
			->assertJsonValidationErrors([
			'username' => self::ERRORS['validations']['username']['username'],
			'password' => self::ERRORS['validations']['password']['password'],
		]);
	}

	public function testPartialCredsLogin()
	{
		$response = $this->postJSON('/api/login', [
			'username' => 'Bill',
		]);

		$response
			->assertStatus(self::UNPROCESSABLE_ENTITY_STATUS_CODE)
			->assertJson(self::ERRORS['message'])
			->assertJsonValidationErrors(
				self::ERRORS['validations']['password']
			);

		$response = $this->postJSON('/api/login', [
			'password' => 'Bill',
		]);

		$response
			->assertJson(self::ERRORS['message'])
			->assertJsonValidationErrors(
				self::ERRORS['validations']['username']
			);
	}

	public function testWrongCredsLogin()
	{
		$response = $this->postJSON('/api/login', [
			'username' => 'Bill',
			'password' => 'Bill',
		]);

		$response->assertJson([
			'code'    => self::UNAUTHORIZED_STATUS_COCDE,
			'message' => 'Invalid Request. Please enter in correct email and password.',
			'error'   => true,
		]);

		// dd($response->json());
	}
}
