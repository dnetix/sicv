@props(['mode' => 'link', 'placeholder' => 'Documento o nombre del cliente…'])

<div x-data="remoteSearch('{{ route('clients.search') }}')" @click.outside="close()" class="relative">
    <input type="search" x-model="query" @input="input()"
           placeholder="{{ $placeholder }}" autocomplete="off"
           class="w-full rounded-md border-line shadow-sm focus:border-accent-deep focus:ring-accent-deep">

    <div x-show="open" x-cloak
         class="absolute z-10 mt-1 max-h-80 w-full overflow-y-auto rounded-md border border-line-soft bg-white shadow-lg">
        <template x-if="results.length === 0">
            <p class="px-4 py-3 text-sm text-ink-soft">No se encontraron clientes.</p>
        </template>

        <template x-for="client in results" :key="client.id">
            @if ($mode === 'link')
                <a :href="client.url" class="block px-4 py-2 hover:bg-cream">
                    <span class="font-medium" x-text="client.name"></span>
                    <span class="ml-2 text-sm text-ink-soft" x-text="client.document_number"></span>
                </a>
            @elseif ($mode === 'contract')
                <a :href="'{{ route('contracts.create') }}?client=' + client.id"
                   class="flex items-center justify-between gap-3 px-4 py-2 hover:bg-cream">
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-bold" x-text="client.name"></span>
                        <span class="text-xs text-ink-soft" x-text="client.document_number"></span>
                    </span>
                    <span class="shrink-0 text-xs text-ink-soft" x-text="client.phone"></span>
                </a>
            @else
                <button type="button"
                        @click="$dispatch('client-selected', client); close(); query = ''"
                        class="block w-full px-4 py-2 text-left hover:bg-cream">
                    <span class="font-medium" x-text="client.name"></span>
                    <span class="ml-2 text-sm text-ink-soft" x-text="client.document_number"></span>
                </button>
            @endif
        </template>

        @if ($mode === 'contract')
            <a href="{{ route('clients.create') }}"
               class="sticky bottom-0 block border-t border-line-faint bg-white px-4 py-2.5 text-xs font-bold text-accent-deep hover:bg-cream">
                ¿Cliente nuevo? Regístrelo aquí →
            </a>
        @endif
    </div>
</div>
