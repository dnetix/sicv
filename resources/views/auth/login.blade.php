@extends('layouts.base')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img class="mx-auto h-20 w-auto" src="{{ asset('images/logo_sicv_big.png') }}" alt="{{ trans('app.name') }}">
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Acceso de usuarios
                </h2>
            </div>

            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                <input type="hidden" name="remember" value="true">
                <div class="rounded-md shadow-sm -space-y-px">
                    <div>
                        <label for="username" class="sr-only">Usuario</label>
                        <input id="username" name="username" type="text" autocomplete="username" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Usuario">
                    </div>
                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm" placeholder="Password">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Recordarme
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Recuperar contraseña
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-400 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Ingresar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
