@props(['name', 'title'])

{{-- Requires an ancestor with x-data="{ modal: null }"; open with @click="modal = '{{ $name }}'". --}}
<div x-show="modal === '{{ $name }}'" x-cloak
     class="fixed inset-0 z-30 flex items-center justify-center p-4"
     @keydown.escape.window="modal = null">
    <div class="absolute inset-0 bg-ink/50" @click="modal = null"></div>

    <div class="relative w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
        <div class="mb-4 flex items-start justify-between">
            <h3 class="font-semibold text-ink">{{ $title }}</h3>
            <button type="button" @click="modal = null" class="text-ink-faint hover:text-ink-soft" aria-label="Cerrar">&times;</button>
        </div>

        {{ $slot }}
    </div>
</div>
