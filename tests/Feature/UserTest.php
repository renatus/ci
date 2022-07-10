<?php

namespace Tests\Feature;

use Faker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * Add site's user.
     *
     * @return void
     */
    public function testUserAdd()
    {
        $faker = Faker\Factory::create();
        // Do NOT use $faker->unique()->email, sometimes it'll give e-mail on non-existing domain.
        // Such e-mail will be considered invalid by 'email:rfc,dns' validator.
        // $faker->unique()->freeEmail uses only gmail.com, yahoo.com and hotmail.com domains.
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->post('/api/v1/register', [
            'name' => $faker->name,
            'email' => $faker->unique()->freeEmail,
            'password' => 'DfBBBnMMl23DwerT',
        ]);

        $response->assertJsonFragment(['token_type' => 'Bearer']);
    }

    /**
     * Login as a site's user.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $user = User::all()->first();
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'application/json',
        ])->post('/api/v1/login', [
            'email' => $user->email,
            'password' => 'DfBBBnMMl23DwerT',
        ]);

        $response->assertJsonFragment(['token_type' => 'Bearer']);
    }
}
