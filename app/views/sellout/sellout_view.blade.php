@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Detalles de saca # {{ $sellout->id() }}</h2>
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
                </tr>
                </thead>
                <tbody>
                @forelse($contracts as $contract)
                    @include('report.partials._presellout_contract_item', ['nochange' => true])
                @empty

                @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection