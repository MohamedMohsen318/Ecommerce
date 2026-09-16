<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\WithTenantDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase, WithTenantDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpTenant();
    }

    protected function tearDown(): void
    {
        $this->tearDownTenant();
        parent::tearDown();
    }

    protected function url(string $path): string
    {
        return 'http://'.$this->tenant->id.'.test'.$path;
    }

    public function test_registering_creates_a_user_and_logs_them_in(): void
    {
        $response = $this->post($this->url('/register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com'], 'tenant');
        $this->assertAuthenticated();
        $response->assertRedirect(route('home'));
    }

    public function test_registering_sends_a_verification_email(): void
    {
        Notification::fake();

        $this->post($this->url('/register'), [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $user = User::where('email', 'jane@example.com')->firstOrFail();

        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
