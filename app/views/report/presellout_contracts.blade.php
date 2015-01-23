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

        <div class="panel panel-warning panel-contract-options">
            <div class="panel-heading">
                <div class="panel-title">Estadisticas de Contratos Presacados</div>
            </div>
            <div class="panel-body">
                <div class="stat">
                    <small>Valor Total de Contratos</small>
                    <h3>$ 120.000.000</h3>
                    <div class="text-muted">En 100 contratos preseleccionados</div>
                </div>
                <div class="stat">
                    <small>Total de peso del oro</small>
                    <h3>12 gramos</h3>
                    <div class="text-muted">En 30 articulos</div>
                </div>
                <div class="stat">
                    <small>Total de abonos para cancelar todo</small>
                    <h3>$ 90.000.000</h3>
                    <div class="text-muted">En 125 meses</div>
                </div>
            </div>
        </div>


    </div>

    <!-- contentpanel -->
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection