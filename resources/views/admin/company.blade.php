@extends('layouts.app')

@section('title', 'Datos de la compraventa')

@section('content')
    <div class="mx-auto max-w-4xl">
        <h1 class="mb-6 text-xl font-bold">Datos de la compraventa</h1>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <form method="POST" action="{{ route('admin.company.update') }}" enctype="multipart/form-data"
                  class="space-y-4 rounded-lg bg-white p-6 border border-line">
                @csrf
                @method('PUT')

                <x-field label="Razón social" name="legal_name" :value="$company->legal_name" required />
                <x-field label="Nombre del establecimiento" name="name" :value="$company->name" required />
                <x-field label="NIT" name="tax_id" :value="$company->tax_id" required />
                <x-field label="Dirección" name="address" :value="$company->address" required />

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <x-field label="Teléfono" name="phone" :value="$company->phone" required />
                    <x-field label="Ciudad" name="city" :value="$company->city" required />
                </div>

                <x-field label="Logotipo (PNG/JPG/GIF, máx. 1MB)" name="logo" type="file" accept="image/*" />

                <div class="flex justify-end">
                    <button type="submit"
                            class="rounded-md bg-accent px-4 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
                        Guardar cambios
                    </button>
                </div>
            </form>

            {{-- Live preview of the printed contract header --}}
            <div class="self-start rounded-lg bg-white p-6 border border-line">
                <h2 class="mb-4 text-sm font-medium text-ink-soft">Vista previa del encabezado impreso</h2>
                <div class="rounded border border-line-soft p-6 text-center text-[13px]">
                    @if ($company->logo_path)
                        <img src="{{ route('company.logo') }}" alt="{{ $company->name }}" class="mx-auto max-h-[100px]">
                    @else
                        <p class="text-xl font-bold">{{ $company->name }}</p>
                    @endif
                    <p class="mt-2">Contrato de compraventa con pacto de retroventa, Artículo 1939 del Código Civil Colombiano</p>
                    <p class="font-bold">{{ $company->address }} - {{ $company->city }} - Tel. {{ $company->phone }}</p>
                    <p class="barcode mt-4">{{ $sampleBarcode }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
