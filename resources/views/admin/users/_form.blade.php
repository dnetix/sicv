<form method="POST" action="{{ $action }}" class="space-y-3">
    @csrf
    @if ($method === 'PUT') @method('PUT') @endif

    <div>
        <label class="mb-1 block text-sm font-medium">Usuario</label>
        <input name="username" required minlength="3" maxlength="30" value="{{ $user?->username }}"
               class="w-full rounded-md border-line text-sm shadow-sm">
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">Nombre</label>
        <input name="name" required minlength="3" value="{{ $user?->name }}"
               class="w-full rounded-md border-line text-sm shadow-sm">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium">Correo</label>
            <input name="email" type="email" value="{{ $user?->email }}"
                   class="w-full rounded-md border-line text-sm shadow-sm">
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Teléfono</label>
            <input name="phone" value="{{ $user?->phone }}"
                   class="w-full rounded-md border-line text-sm shadow-sm">
        </div>
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="mb-1 block text-sm font-medium">Rol</label>
            <select name="role" required class="w-full rounded-md border-line text-sm shadow-sm">
                @foreach (\App\Enums\UserRole::cases() as $role)
                    <option value="{{ $role->value }}" @selected($user?->role === $role)>{{ $role->label() }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Estado</label>
            <select name="active" required class="w-full rounded-md border-line text-sm shadow-sm">
                <option value="1" @selected($user?->active ?? true)>Activo</option>
                <option value="0" @selected($user !== null && ! $user->active)>Inactivo</option>
            </select>
        </div>
    </div>
    <div>
        <label class="mb-1 block text-sm font-medium">
            {{ $user === null ? 'Contraseña' : 'Nueva contraseña (dejar vacío para no cambiarla)' }}
        </label>
        <input name="password" type="password" @required($user === null) minlength="6" autocomplete="new-password"
               class="w-full rounded-md border-line text-sm shadow-sm">
    </div>

    <button type="submit"
            class="w-full rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
        {{ $user === null ? 'Crear usuario' : 'Guardar cambios' }}
    </button>
</form>
