@props(['range', 'itemTypes' => null, 'expenseTypes' => null])

<form method="GET" action="" class="print:hidden mb-5 flex flex-wrap items-end gap-3 rounded-lg border border-line bg-white px-4 py-3.5">
    <input type="hidden" name="filter" value="1">

    <div>
        <label for="from" class="mb-1 block text-[11px] text-ink-soft">Desde</label>
        <input id="from" name="from" type="date" value="{{ request('from', $range->from->toDateString()) }}"
               class="rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
    </div>
    <div>
        <label for="to" class="mb-1 block text-[11px] text-ink-soft">Hasta</label>
        <input id="to" name="to" type="date" value="{{ request('to', $range->to->toDateString()) }}"
               class="rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
    </div>

    @if ($itemTypes !== null)
        <div>
            <label for="item_type_id" class="mb-1 block text-[11px] text-ink-soft">Tipo de artículo</label>
            <select id="item_type_id" name="item_type_id"
                    class="rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
                <option value="">Todos</option>
                @foreach ($itemTypes as $type)
                    <option value="{{ $type->id }}" @selected(request('item_type_id') == $type->id)>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    @if ($expenseTypes !== null)
        <div>
            <label for="expense_type_id" class="mb-1 block text-[11px] text-ink-soft">Tipo de gasto</label>
            <select id="expense_type_id" name="expense_type_id"
                    class="rounded-md border-line text-sm focus:border-accent-deep focus:ring-accent-deep">
                <option value="">Todos</option>
                @foreach ($expenseTypes as $type)
                    <option value="{{ $type->id }}" @selected(request('expense_type_id') == $type->id)>{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
    @endif

    {{ $slot }}

    <button type="submit" class="rounded-md bg-accent px-4.5 py-2 text-sm font-bold text-ink hover:bg-accent-strong">
        Filtrar
    </button>
</form>
