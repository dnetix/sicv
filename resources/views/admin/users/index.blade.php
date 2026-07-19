@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <div x-data="{ modal: null }">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-bold">Usuarios</h1>
            <button type="button" @click="modal = 'create'"
                    class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                Nuevo usuario
            </button>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-md border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="rounded-lg bg-white border border-line">
            <table class="min-w-full divide-y divide-line-soft text-sm">
                <thead class="bg-cream text-left text-xs uppercase tracking-wide text-ink-soft">
                    <tr>
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Nombre</th>
                        <th class="px-4 py-2">Correo</th>
                        <th class="px-4 py-2">Teléfono</th>
                        <th class="px-4 py-2">Rol</th>
                        <th class="px-4 py-2">Estado</th>
                        <th class="px-4 py-2 print:hidden"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-faint">
                    @foreach ($users as $user)
                        <tr class="hover:bg-cream">
                            <td class="px-4 py-2 font-medium">{{ $user->username }}</td>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->phone }}</td>
                            <td class="px-4 py-2">{{ $user->role->label() }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'rounded-full px-2 py-0.5 text-xs font-medium',
                                    'bg-emerald-100 text-emerald-800' => $user->active,
                                    'bg-line-soft text-ink-soft' => ! $user->active,
                                ])>
                                    {{ $user->active ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right print:hidden">
                                <button type="button" @click="modal = 'edit-{{ $user->id }}'"
                                        class="text-sm text-ink-soft underline">Editar</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <x-modal name="create" title="Nuevo usuario">
            @include('admin.users._form', ['action' => route('admin.users.store'), 'user' => null, 'method' => 'POST'])
        </x-modal>

        @foreach ($users as $user)
            <x-modal name="edit-{{ $user->id }}" title="Editar usuario: {{ $user->username }}">
                @include('admin.users._form', ['action' => route('admin.users.update', $user), 'user' => $user, 'method' => 'PUT'])
            </x-modal>
        @endforeach
    </div>
@endsection
