@extends('layouts.app')

@section('title', 'Nuevo artículo')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-6 text-xl font-bold">Nuevo artículo para la venta</h1>

        <form method="POST" action="{{ route('store.store') }}" class="space-y-4 rounded-lg bg-white p-6 border border-line">
            @csrf

            <x-field label="Descripción del artículo" name="description" required>
                <textarea id="description" name="description" rows="3" required autofocus
                          class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">{{ old('description') }}</textarea>
            </x-field>

            <x-field label="Tipo de artículo" name="item_type_id" required>
                <select id="item_type_id" name="item_type_id" required
                        class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">
                    <option value="">Seleccione…</option>
                    @foreach ($itemTypes as $type)
                        <option value="{{ $type->id }}" @selected(old('item_type_id') == $type->id)>{{ $type->name }}</option>
                    @endforeach
                </select>
            </x-field>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <x-field label="Valor de compra" name="cost" type="number" min="0" required :value="old('cost')" />
                <x-field label="Valor de venta" name="price" type="number" min="0" required :value="old('price')" />
                <x-field label="Cantidad" name="stock" type="number" min="1" required :value="old('stock', 1)" />
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('store.index') }}" class="rounded-md px-4 py-2 text-sm text-ink-soft hover:bg-cream">Cancelar</a>
                <button type="submit"
                        class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                    Guardar artículo
                </button>
            </div>
        </form>
    </div>
@endsection
