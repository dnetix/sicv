@extends('layouts.print')

@section('title', "Nota de Cobro NC{$sale->id}")

@section('content')
    <div class="mx-auto max-w-3xl px-8 py-6 text-[13px] leading-snug">
        <h2 class="text-center text-lg font-bold uppercase">Nota de Cobro</h2>

        <div class="mt-2 text-center">
            <p>Emitida por:</p>
            @if ($company->logo_path)
                <img src="{{ route('company.logo') }}" alt="{{ $company->name }}" class="mx-auto max-h-[100px]">
            @else
                <p class="text-xl font-bold">{{ $company->name }}</p>
            @endif
            <p class="font-bold">{{ $company->address }} - {{ $company->city }} - Tel. {{ $company->phone }}</p>
        </div>

        <div class="mt-4 text-center">
            <p class="barcode">{{ $barcode }}</p>
            <h3 class="text-base font-bold">Nota Cobro No. <span>{{ $sale->id }}</span></h3>
        </div>

        <p class="mt-4 text-justify">
            Yo, <strong>{{ mb_strtoupper($sale->client->name) }}</strong> identificado con
            {{ $sale->client->document_type === 'CE' ? 'cédula de extranjería' : 'cédula de ciudadanía' }}
            No. <strong>{{ $sale->client->document_number }}</strong>, expedida en
            <strong>{{ $sale->client->document_issue_place }}</strong> domiciliado en
            <strong>{{ $sale->client->city }}</strong> Tel: <strong>{{ $sale->client->phone ?: $sale->client->mobile }}</strong>,
            en calidad de COMPRADOR(A), del producto(s) que a continuación se describe(n):
        </p>

        <h4 class="mt-4 font-bold">Descripción detallada de articulo</h4>
        <table class="mt-2 w-full border-collapse text-[13px]">
            <thead>
                <tr>
                    <th class="border border-slate-400 px-2 py-1">Articulo</th>
                    <th class="border border-slate-400 px-2 py-1">Descripcion</th>
                    <th class="border border-slate-400 px-2 py-1">Cantidad</th>
                    <th class="border border-slate-400 px-2 py-1">Precio</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sale->items as $item)
                    <tr>
                        <td class="border border-slate-400 px-2 py-1 text-center">{{ $item->store_item_id }}</td>
                        <td class="border border-slate-400 px-2 py-1">{{ $item->storeItem->description }}</td>
                        <td class="border border-slate-400 px-2 py-1 text-center">{{ $item->quantity }}</td>
                        <td class="border border-slate-400 px-2 py-1 text-right">{{ money($item->price) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="3" class="border border-slate-400 px-2 py-1 text-right">Total</th>
                    <th class="border border-slate-400 px-2 py-1 text-right">{{ money($sale->items->sum('price')) }}</th>
                </tr>
            </tbody>
        </table>

        <div class="mt-4 space-y-2 text-justify">
            <p>Soy consciente y plenamente capaz del negocio jurídico que he realizado, naturaleza, consecuencias,
                características, condiciones, calidad entre otras; Se me han explicado satisfactoriamente las obligaciones
                y derechos, que poseo</p>
            <p>No opera el derecho de retracto, ya que no se realizó el negocio bajo la figura de sistema de financiación
                o venta a distancia. (Art. 47 Ley 1480 de 2011)</p>
        </div>

        <div class="mt-4 space-y-2 text-justify">
            @if ($sale->warranty_days === 0)
                <p>Acepto en calidad de COMPRADOR. Que este artículo usado o de segunda, antes descrito NO tiene garantía.
                    Ya que ha expirado el término de garantía legal.</p>
            @else
                <p>Acepto en calidad de COMPRADOR. Que este artículo usado o de segunda, antes descrito tiene una garantía
                    de {{ $sale->warranty_days }} días. No opera la garantía por mal uso, manejo o maltrato al producto por
                    parte del consumidor.</p>
            @endif
            <p class="font-medium">TANTO VENDEDOR COMO COMPRADOR HAN LEÍDO, COMPRENDIDO Y ACEPTADO EL TEXTO DE ESTE CONTRATO.</p>
            <p>En constancia de lo anterior lo firman las partes en {{ $company->city }} a los
                <strong>{{ $sale->sold_at->translatedFormat('j \d\í\a\s, \d\e\l \m\e\s \d\e F \d\e\l \a\ñ\o Y') }}</strong></p>
        </div>

        <div class="mt-14 flex items-end justify-between gap-6">
            <div class="w-64 border-t border-slate-800 pt-1">
                <p class="font-medium">EL COMPRADOR</p>
                <p>{{ $sale->client->document_type }}</p>
            </div>

            <div class="h-24 w-20 border border-slate-400"></div>

            <div class="w-64 border-t border-slate-800 pt-1">
                <p class="font-medium">EL VENDEDOR</p>
                <p>CC</p>
            </div>
        </div>
    </div>
@endsection
