<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::query()->orderBy('username')->get(),
            'roles' => UserRole::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        User::query()->create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $this->validated($request, $user);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        // An administrator cannot deactivate or demote their own account.
        if ($user->is($request->user())) {
            $validated['active'] = true;
            $validated['role'] = $user->role;
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('status', 'Usuario actualizado exitosamente.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?User $user = null): array
    {
        return $request->validate(
            [
                'username' => ['required', 'string', 'min:3', 'max:30', Rule::unique('users')->ignore($user)],
                'name' => ['required', 'string', 'min:3', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:45'],
                'role' => ['required', new Enum(UserRole::class)],
                'active' => ['required', 'boolean'],
                'password' => [$user === null ? 'required' : 'nullable', Password::min(6)],
            ],
            [],
            [
                'username' => 'usuario',
                'name' => 'nombre',
                'email' => 'correo electrónico',
                'phone' => 'teléfono',
                'role' => 'rol',
                'active' => 'activo',
                'password' => 'contraseña',
            ],
        );
    }
}
