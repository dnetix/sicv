@foreach (['status' => 'border-emerald-300 bg-emerald-50 text-emerald-800', 'error' => 'border-red-300 bg-red-50 text-red-800'] as $key => $classes)
    @if (session($key))
        <div x-data="{ show: true }" x-show="show" x-cloak
             class="mb-4 flex items-start justify-between rounded-md border px-4 py-3 text-sm {{ $classes }} print:hidden">
            <p>{{ session($key) }}</p>
            <button type="button" @click="show = false" class="ml-4 font-bold" aria-label="Cerrar">&times;</button>
        </div>
    @endif
@endforeach
