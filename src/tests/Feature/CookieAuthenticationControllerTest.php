<?php declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CookieAuthenticationControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return void
     */
    public function testLoginSuccess(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $response->assertJson(['user' => [
            'email' => 'test@example.com',
        ]]);
    }

    /**
     * @return void
     */
    public function testLoginFailed(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->postJson('login', [
            'email' => 'failed@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(500);
    }

    public function testRegisterSuccess(): void
    {
        $response = $this->postJson('register', [
            'name' => 'testUser',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['user' => [
            'name' => 'testUser',
            'email' => 'test@example.com',
        ]]);
    }

    public function testRegisterFaild(): void
    {
        $response = $this->postJson('register', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(422);
    }
}
