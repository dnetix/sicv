@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Contratos Vencidos</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="form-group">
        <div class="ckbox ckbox-warning">
            <input type="checkbox" id="checkboxWarning" onclick="selectAll(this)" />
            <label for="checkboxWarning">Seleccionar Todos</label>
        </div>
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
            @include('report.partials._presellout_contract_item')
        @empty

        @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- contentpanel -->
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection