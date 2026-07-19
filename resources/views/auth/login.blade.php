@extends('layouts.app')

@section('title', 'Iniciar sesión')

@section('content')
    <div class="flex min-h-screen items-center justify-center bg-paper p-4">
        <div class="flex w-full max-w-4xl overflow-hidden rounded-lg border border-line bg-white shadow-sm">
            <div class="hidden w-[45%] flex-col justify-between bg-night p-10 text-accent-tint sm:flex">
                <div class="flex flex-col gap-4">
                    @if ($company->logo_path)
                        <img src="{{ route('company.logo') }}" alt="" class="h-12 w-12 rounded-full bg-white/90 object-contain p-1">
                    @else
                        <span class="flex h-12 w-12 items-center justify-center rounded-full border border-dashed border-accent bg-accent/15 text-lg font-bold text-accent">
                            {{ mb_substr($company->name, 0, 1) }}
                        </span>
                    @endif
                    <h1 class="text-2xl font-bold leading-tight">{{ $company->name }}</h1>
                    <p class="text-[13px] text-night-soft">Sistema de contratos, almacén y reportes</p>
                </div>
                <p class="text-[11px] text-night-faint">SICV · uso interno</p>
            </div>

            <div class="flex flex-1 items-center justify-center px-8 py-16">
                <form method="POST" action="{{ route('login.store') }}" class="flex w-full max-w-xs flex-col gap-4">
                    @csrf

                    <h2 class="text-xl font-bold">Iniciar sesión</h2>

                    <div>
                        <label for="username" class="mb-1.5 block text-xs font-bold">Usuario</label>
                        <input id="username" name="username" type="text" value="{{ old('username') }}"
                               required autofocus autocomplete="username" placeholder="nombre de usuario"
                               class="w-full rounded-md border-line placeholder-ink-faint focus:border-accent-deep focus:ring-accent-deep">
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-bold">Contraseña</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full rounded-md border-line placeholder-ink-faint focus:border-accent-deep focus:ring-accent-deep">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="remember" value="1"
                               class="rounded border-line text-accent-deep focus:ring-accent-deep">
                        Mantener sesión iniciada
                    </label>

                    <button type="submit"
                            class="h-11 rounded-md bg-accent text-sm font-bold text-ink hover:bg-accent-strong">
                        Ingresar
                    </button>

                    <p class="text-center text-[11px] text-ink-faint">Solo usuarios activos · sin auto-registro</p>
                </form>
            </div>
        </div>
    </div>
@endsection
