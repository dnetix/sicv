@props(['label', 'name', 'type' => 'text', 'value' => null, 'required' => false])

<div>
    <label for="{{ $name }}" class="mb-1 block text-sm font-medium">
        {{ $label }}@if ($required)<span class="text-red-500">*</span>@endif
    </label>

    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        <input id="{{ $name }}" name="{{ $name }}" type="{{ $type }}"
               value="{{ old($name, $value) }}" @required($required)
               {{ $attributes->merge(['class' => 'w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep']) }}>
    @endif

    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
