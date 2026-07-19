@props(['label', 'name', 'value' => null, 'required' => false])

{{-- Text input with autocomplete sourced from issue places already registered on other clients. --}}
<div x-data="remoteSearch('{{ route('clients.cities') }}')">
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium">
        {{ $label }}@if ($required)<span class="text-red-500">*</span>@endif
    </label>

    <input id="{{ $name }}" name="{{ $name }}" type="text" list="{{ $name }}-options"
           value="{{ old($name, $value) }}" @required($required)
           x-model="query" @input="input()" autocomplete="off"
           class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">

    <datalist id="{{ $name }}-options">
        <template x-for="city in results" :key="city">
            <option :value="city"></option>
        </template>
    </datalist>

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
