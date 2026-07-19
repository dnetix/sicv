<?php

namespace Tests\Feature\Admin;

use App\Enums\UserRole;
use App\Models\AmountOverride;
use App\Models\CompanySetting;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $employee;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->administrator()->create();
        $this->employee = User::factory()->create();
    }

    public function test_admin_screens_require_administrator_role(): void
    {
        foreach (['admin.company.edit', 'admin.users.index', 'admin.overrides.index'] as $route) {
            $this->actingAs($this->employee)->get(route($route))->assertForbidden();
        }
    }

    public function test_admin_can_update_company_settings(): void
    {
        CompanySetting::query()->create([
            'legal_name' => 'Vieja S.A.S.', 'tax_id' => '1', 'name' => 'Vieja',
            'address' => 'Calle 1', 'phone' => '1', 'city' => 'La Ceja',
        ]);

        $this->actingAs($this->admin)->put(route('admin.company.update'), [
            'legal_name' => 'Compraventa El Diamante S.A.S.',
            'tax_id' => '900123456-1',
            'name' => 'Compraventa El Diamante',
            'address' => 'Carrera 20 # 1-23',
            'phone' => '5551234',
            'city' => 'La Ceja',
        ])->assertRedirect(route('admin.company.edit'));

        $this->assertSame('Compraventa El Diamante', CompanySetting::current()->name);
    }

    public function test_admin_can_create_and_update_users(): void
    {
        $this->actingAs($this->admin)->post(route('admin.users.store'), [
            'username' => 'maria',
            'name' => 'Maria Gomez',
            'role' => UserRole::Employee->value,
            'active' => 1,
            'password' => 'secreta1',
        ])->assertRedirect(route('admin.users.index'));

        $user = User::query()->where('username', 'maria')->sole();
        $this->assertTrue(Hash::check('secreta1', $user->password));

        $this->actingAs($this->admin)->put(route('admin.users.update', $user), [
            'username' => 'maria',
            'name' => 'Maria Gomez',
            'role' => UserRole::Employee->value,
            'active' => 0,
            'password' => '',
        ]);

        $this->assertFalse($user->fresh()->active);
    }

    public function test_admin_cannot_deactivate_or_demote_own_account(): void
    {
        $this->actingAs($this->admin)->put(route('admin.users.update', $this->admin), [
            'username' => $this->admin->username,
            'name' => $this->admin->name,
            'role' => UserRole::Employee->value,
            'active' => 0,
            'password' => '',
        ]);

        $fresh = $this->admin->fresh();
        $this->assertTrue($fresh->active);
        $this->assertSame(UserRole::Administrator, $fresh->role);
    }

    public function test_overrides_screen_lists_differences(): void
    {
        $contract = Contract::factory()->create();

        AmountOverride::query()->create([
            'operation' => 'redeem',
            'auditable_type' => $contract->getMorphClass(),
            'auditable_id' => $contract->id,
            'computed_amount' => 1_100_000,
            'entered_amount' => 1_000_000,
            'user_id' => $this->employee->id,
            'created_at' => now(),
        ]);

        $this->actingAs($this->admin)->get(route('admin.overrides.index'))
            ->assertOk()
            ->assertSee('Cancelación de contrato')
            ->assertSee($this->employee->name)
            ->assertSee(money(1_100_000))
            ->assertSee(money(1_000_000));
    }

    public function test_user_can_update_own_profile_and_password(): void
    {
        $user = User::factory()->create(['password' => 'vieja123']);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => 'Nombre Nuevo',
            'email' => 'nuevo@example.com',
            'phone' => '3001234567',
            'current_password' => 'vieja123',
            'password' => 'nueva123',
            'password_confirmation' => 'nueva123',
        ])->assertRedirect(route('profile.edit'));

        $user->refresh();
        $this->assertSame('Nombre Nuevo', $user->name);
        $this->assertTrue(Hash::check('nueva123', $user->password));
    }

    public function test_password_change_requires_correct_current_password(): void
    {
        $user = User::factory()->create(['password' => 'vieja123']);

        $this->actingAs($user)->put(route('profile.update'), [
            'name' => $user->name,
            'current_password' => 'incorrecta',
            'password' => 'nueva123',
            'password_confirmation' => 'nueva123',
        ])->assertSessionHasErrors('current_password');

        $this->assertTrue(Hash::check('vieja123', $user->fresh()->password));
    }

    public function test_dashboard_shows_todays_contracts_with_total(): void
    {
        $today = Contract::factory()->create(['amount' => 150_000, 'started_at' => now()]);
        Contract::factory()->create(['amount' => 999_000, 'started_at' => now()->subDays(2)]);

        $this->actingAs($this->employee)->get(route('dashboard'))
            ->assertOk()
            ->assertSee(route('contracts.show', $today))
            ->assertSee(money(150_000))
            ->assertDontSee(money(999_000));
    }
}
