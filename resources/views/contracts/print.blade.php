@extends('layouts.print')

@section('title', "Contrato No. {$contract->id}")

@section('content')
    <div class="relative mx-auto max-w-3xl px-8 py-6 text-[13px] leading-snug">
        @if ($isCopy)
            <div class="pointer-events-none absolute inset-0 flex items-center justify-center">
                <span class="rotate-[-30deg] text-7xl font-bold tracking-widest text-slate-200">DUPLICADO</span>
            </div>
        @endif

        <h2 class="text-center text-lg font-bold uppercase">Contrato de Compraventa</h2>

        <div class="mt-2 text-center">
            @if ($company->logo_path)
                <img src="{{ route('company.logo') }}" alt="{{ $company->name }}" class="mx-auto max-h-[100px]">
            @else
                <p class="text-xl font-bold">{{ $company->name }}</p>
            @endif
            <p>Contrato de compraventa con pacto de retroventa, Artículo 1939 del Código Civil Colombiano</p>
            <p class="font-bold">{{ $company->address }} - {{ $company->city }} - Tel. {{ $company->phone }}</p>
        </div>

        <div class="mt-4 text-center">
            <p class="barcode">{{ $barcode }}</p>
            <h3 class="text-base font-bold">Contrato No. <span>{{ $contract->id }}</span></h3>
        </div>

        <div class="mt-4 flex justify-between font-medium">
            <span>Fecha Contrato: <strong>{{ $contract->started_at->format('d/m/Y') }}</strong></span>
            <span>Fecha Vencimiento: <strong>{{ $contract->dueDate()->format('d/m/Y') }}</strong></span>
        </div>

        <p class="mt-4 text-justify">
            Entre los suscritos <strong>{{ mb_strtoupper($contract->client->name) }}</strong> identificado con
            {{ $contract->client->document_type === 'CE' ? 'cédula de extranjería' : 'cédula de ciudadanía' }}
            No. <strong>{{ $contract->client->document_number }}</strong>, expedida en
            <strong>{{ $contract->client->document_issue_place }}</strong> domiciliado en
            <strong>{{ $contract->client->city }}</strong> Tel: <strong>{{ $contract->client->phone ?: $contract->client->mobile }}</strong>,
            mayor de edad quien obra en nombre propio y se denomina para efectos del presente contrato EL VENDEDOR de una
            parte; Y por otra parte quien para los efectos del presente contrato se denominará EL COMPRADOR en
            representación del establecimiento de comercio denominado <strong>{{ $company->name }}</strong> Razón Social
            <strong>{{ $company->legal_name }}</strong> NIT <strong>{{ $company->tax_id }}</strong> Ubicado en la
            <strong>{{ $company->address }} - {{ $company->city }} - Tel. {{ $company->phone }}</strong> Manifestamos que
            hemos celebrado un contrato de compraventa sobre el(los) siguiente(s) bien(es) mueble(s) que a continuación se
            identifica(n)
        </p>

        <h4 class="mt-4 font-bold">Descripción detallada de articulo objeto de esta compraventa</h4>
        <p class="whitespace-pre-line">{{ $contract->description }}</p>
        <p class="mt-2">El precio de la compraventa es la suma de: <strong>{{ money($contract->amount) }}</strong></p>

        <p class="mt-4 text-justify">
            EL VENDEDOR transfiere al COMPRADOR a título de compraventa el derecho de dominio y posesión que tiene y ejerce
            sobre los anteriores artículos y declara que los bienes que transfiere, los adquirió lícitamente, no fue su
            importador, son de su exclusiva propiedad, los posee de manera regular, pública y pacifica, están libres de
            gravamen, limitación al dominio, pleitos pendientes y embargos, con la obligación de salir al saneamiento en
            casos de ley.
        </p>

        <h4 class="mt-4 font-bold">Clausulas accesorias que rigen el presente contrato</h4>
        <div class="space-y-2 text-justify">
            <p><strong>Primera:</strong> Los contratantes de conformidad con el articulo 1939 del Código Civil Colombiano,
                EL VENDEDOR se reserva la facultad de recobrar los articulos vendidos por medio de este contrato, pagando al
                COMPRADOR como Precio de retroventa la suma de: <strong>{{ money($contract->buyBackPrice()) }}</strong></p>
            <p><strong>Segunda:</strong> El derecho que nace del pacto de retroventa del presente contrato, no podrá cederse
                a ningún titulo. En caso de perdida de este contrato EL VENDEDOR se obliga a dar noticia inmediata al
                COMPRADOR y éste, sólo exhibirá el articulo descrito para la terminación del presente contrato.</p>
            <p><strong>Tercera:</strong> EL VENDEDOR y EL COMPRADOR pactan que la facultad de retroventa del presente
                contrato la podrá ejercer EL VENDEDOR dentro del término de
                <strong>{{ $contract->term_months * 30 }} días</strong> contados a partir de la firma del presente documento.</p>
            <p><strong>Cuarta:</strong> Las partes aquí firmantes, hemos establecido que en caso de deterioro o pérdida de
                los articulos descritos, ocasionada por fuerza mayor o caso fortuito, se exonerará de cualquier
                responsabilidad AL COMPRADOR.</p>
            <p><strong>Quinta:</strong> Las controversias relativas al presente contrato, se resolverán por un tribunal de
                arbitramiento de conformidad con las disposiciones que rigen la materia nombrado por la Cámara de Comercio
                de esta ciudad.</p>
        </div>

        <p class="mt-4 font-medium">TANTO VENDEDOR COMO COMPRADOR HAN LEÍDO, COMPRENDIDO Y ACEPTADO EL TEXTO DE ESTE CONTRATO.</p>
        <p>En constancia de lo anterior lo firman las partes en {{ $company->city }} a los
            <strong>{{ $contract->started_at->translatedFormat('j \d\í\a\s, \d\e\l \m\e\s \d\e F \d\e\l \a\ñ\o Y') }}</strong></p>

        <div class="mt-14 flex items-end justify-between gap-6">
            <div class="w-64 border-t border-slate-800 pt-1">
                <p class="font-medium">EL VENDEDOR</p>
                <p>{{ $contract->client->document_type }}</p>
            </div>

            <div class="h-24 w-20 border border-slate-400"></div>

            <div class="w-64 border-t border-slate-800 pt-1">
                <p class="font-medium">EL COMPRADOR</p>
                <p>CC</p>
            </div>
        </div>
    </div>
@endsection
