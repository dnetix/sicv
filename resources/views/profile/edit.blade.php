@extends('layouts.app')

@section('title', 'Mis datos')

@section('content')
    <div class="mx-auto max-w-xl">
        <h1 class="mb-6 text-xl font-bold">Mis datos</h1>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4 rounded-lg bg-white p-6 border border-line">
            @csrf
            @method('PUT')

            <x-field label="Usuario" name="username_display" :value="auth()->user()->username" disabled
                     class="w-full rounded-md border-line-soft bg-cream text-ink-soft" />

            <x-field label="Nombre" name="name" :value="auth()->user()->name" required />
            <x-field label="Correo electrónico" name="email" type="email" :value="auth()->user()->email" />
            <x-field label="Teléfono" name="phone" :value="auth()->user()->phone" />

            <div class="border-t border-line-soft pt-4">
                <h2 class="mb-3 text-sm font-medium text-ink">Cambiar contraseña (opcional)</h2>
                <div class="space-y-3">
                    <x-field label="Contraseña actual" name="current_password" type="password" autocomplete="current-password" />
                    <x-field label="Nueva contraseña" name="password" type="password" autocomplete="new-password" />
                    <x-field label="Confirmar nueva contraseña" name="password_confirmation" type="password" autocomplete="new-password" />
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                        class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
@endsection
