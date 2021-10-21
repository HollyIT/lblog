<?php

namespace Tests\Feature\API\Auth;

use App\Models\User;
use Database\Seeders\PostSeeder;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TokenTest extends TestCase
{
    /** @test */
    public function it_issues_api_tokens()
    {
        $this->makeUser('admin');
        $response = $this->postJson('api/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);

        $response->assertStatus(200)
            ->assertJson(function (AssertableJson $json) {
                $json
                    ->whereType('data.id', 'integer')
                    ->whereType('data.name', 'string')
                    ->whereType('meta.token', 'string')
                    ->etc();
            });
    }

    /** @test */
    public function it_authenticates_via_token()
    {
        $this->seed(PostSeeder::class);
        $this->makeUser('admin');
        $this->getJson('api/posts?include=tags,user')
            ->assertStatus(401);

        $response = $this->postJson('api/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);


        $response->assertStatus(200);

        $data = json_decode($response->getContent());
        $token = $data->meta->token;

        $this->getJson('api/posts?include=tags,user', [
            'Authorization' => 'Bearer '.$token,
        ])->assertStatus(200);
    }

    /** @test */
    public function it_revokes_tokens()
    {
        $this->makeUser('admin');

        $response = $this->postJson('api/login', [
            'email' => 'admin@example.com',
            'password' => 'secret',
        ]);
        $response->assertStatus(200);
        $data = json_decode($response->getContent());
        $token = $data->meta->token;

        $this->getJson('api/posts?include=tags,user', [
            'Authorization' => 'Bearer '.$token,
        ])->assertStatus(200);

        $this->postJson('api/logout', [], [
            'Authorization' => 'Bearer '.$token,
        ])->assertStatus(204);
        $this->assertEmpty(User::first()->tokens);
    }
}
