<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ApiLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:install');
    }
    /**
    * @group apilogintests
    */    
    public function testLogin() {
        $user = \App\Models\User::factory(1)->create()->first();
        $this->json('POST','/api/login',[
            "email" => $user->email,
            "password" => 'password'
        ],['Accept' => 'application/json'])
            ->assertStatus(201)
            ->assertJsonStructure(['id','name','email','token']);
    }
    /**
     * @group apilogintests
     */
    public function testOauthLogin() {
        $oauth_client_id = 2;
        $oauth_client = DB::table('oauth_clients')->find($oauth_client_id);
        $user = \App\Models\User::factory(1)->create()->first();
        $body = [
            'username' => $user->email,
            'password' => 'password',
            'client_id' => $oauth_client_id,
            'client_secret' => $oauth_client->secret,
            'grant_type' => 'password',
            'scope' => '*'
        ];
        $this->json('POST','/oauth/token',$body,['Accept' => 'application/json'])
            ->assertStatus(200)
            ->assertJsonStructure(['token_type','expires_in','access_token','refresh_token']);
    }
}
