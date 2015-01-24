@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Contratos seleccionados para terminar</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div id="contract_statistics">
        @include('report.partials._contracts_statistics')
    </div>

    <div class="text-center mb20">
        <a href="{{ route('sellout.process') }}" class="btn btn-primary btn-lg">Continuar con el proceso de saca</a>
    </div>

    <div class="expired-contracts">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Contrato</th>
                <th>Informaci&oacute;n de Contrato</th>
                <th>Valor</th>
                <th>Fecha</th>
                <th>En Meses</th>
                <th>&Uacute;ltimo Abono</th>
                <th>Presaca</th>
            </tr>
            </thead>
            <tbody>
            @forelse($contracts as $contract)
                @include('report.partials._presellout_contract_item', ['remove' => true])
            @empty

            @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection