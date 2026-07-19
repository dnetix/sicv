<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $this->get('/login')->assertOk();
    }

    public function test_user_can_authenticate_with_valid_credentials(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'password' => 'secret',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_user_cannot_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create(['password' => 'secret']);

        $response = $this->from('/login')->post('/login', [
            'username' => $user->username,
            'password' => 'wrong',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login')->assertSessionHasErrors('username');
    }

    public function test_inactive_user_cannot_authenticate(): void
    {
        $user = User::factory()->inactive()->create(['password' => 'secret']);

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'secret',
        ]);

        $this->assertGuest();
    }

    public function test_legacy_user_authenticates_and_password_is_upgraded_to_bcrypt(): void
    {
        $user = User::factory()->legacy('miclave')->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'miclave',
        ]);

        $this->assertAuthenticatedAs($user);

        $user->refresh();
        $this->assertNull($user->legacy_password_hash);
        $this->assertTrue(Hash::check('miclave', $user->password));
    }

    public function test_legacy_user_with_wrong_password_is_rejected_and_not_upgraded(): void
    {
        $user = User::factory()->legacy('miclave')->create();

        $this->post('/login', [
            'username' => $user->username,
            'password' => 'otra',
        ]);

        $this->assertGuest();

        $user->refresh();
        $this->assertNull($user->password);
        $this->assertNotNull($user->legacy_password_hash);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/logout')->assertRedirect('/login');

        $this->assertGuest();
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_see_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/')->assertOk()->assertSee($user->name);
    }
}
