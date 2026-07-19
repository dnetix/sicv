@extends('layouts.app')

@section('title', 'Nuevo cliente')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-6 text-xl font-bold">Nuevo cliente</h1>

        <form method="POST" action="{{ route('clients.store') }}" class="space-y-4 rounded-lg bg-white p-6 border border-line">
            @csrf

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-field label="Tipo" name="document_type" required>
                    <select id="document_type" name="document_type"
                            class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                        <option value="CC" @selected(old('document_type', 'CC') === 'CC')>Cédula de ciudadanía</option>
                        <option value="CE" @selected(old('document_type') === 'CE')>Cédula de extranjería</option>
                    </select>
                </x-field>

                <div class="sm:col-span-2">
                    <x-field label="Número de documento" name="document_number" required autofocus />
                </div>
            </div>

            <x-field label="Nombre completo" name="name" required />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-city-field label="Lugar de expedición" name="document_issue_place" required />
                <x-city-field label="Ciudad" name="city" required />
            </div>

            <x-field label="Dirección" name="address" />

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <x-field label="Teléfono" name="phone" />
                <x-field label="Celular" name="mobile" />
            </div>

            <x-field label="Correo electrónico" name="email" type="email" />

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('clients.index') }}" class="rounded-md px-4 py-2 text-sm text-ink-soft hover:bg-cream">
                    Cancelar
                </a>
                <button type="submit"
                        class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                    Guardar cliente
                </button>
            </div>
        </form>
    </div>
@endsection
