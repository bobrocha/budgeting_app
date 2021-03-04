<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\Models\User;
use App\Box;

class ExampleTest extends TestCase
{
	/**
	 * A basic test example.
	 *
	 * @return void
	 */
	public function testBasicTest()
	{
		$user = factory(User::class)->create();

		Passport::actingAs(
			$user,
			['create-servers']
		);

		$this->assertTrue(true, true);
	}
}
