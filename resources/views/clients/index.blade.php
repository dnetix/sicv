@extends('layouts.app')

@section('title', 'Buscar clientes')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-xl font-bold">Buscar clientes</h1>
            <a href="{{ route('clients.create') }}"
               class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                Nuevo cliente
            </a>
        </div>

        <div class="rounded-lg bg-white p-6 border border-line">
            <x-client-search />
            <p class="mt-3 text-sm text-ink-soft">
                Busque por número de documento o por nombre. Seleccione un resultado para ver su historial.
            </p>
        </div>
    </div>
@endsection
