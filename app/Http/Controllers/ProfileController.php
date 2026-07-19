<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('profile.edit');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'min:5', 'max:255'],
                'email' => ['nullable', 'email', 'max:255'],
                'phone' => ['nullable', 'string', 'max:45'],
                'current_password' => ['required_with:password', 'nullable', 'current_password'],
                'password' => ['nullable', 'confirmed', Password::min(6)],
            ],
            [],
            [
                'name' => 'nombre',
                'email' => 'correo electrónico',
                'phone' => 'teléfono',
                'current_password' => 'contraseña actual',
                'password' => 'nueva contraseña',
            ],
        );

        $user = $request->user();

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('status', 'Se han actualizado sus datos.');
    }
}
