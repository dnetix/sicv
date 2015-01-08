@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-edit"></i> Nuevo Contrato</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ date_print_format(date('Y-m-d')) }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    {{ Form::open(['class' => 'form-horizontal', 'route' => 'contract.store', 'onsubmit' => 'return validateContract();']) }}
    @include('layouts.partials._errors')
    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-success" id="client_search_panel" {{ isset($client) ? 'style="display: none;"' : '' }}>
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-2">
                            {{ Form::label('client_search', 'Buscar Cliente', ['class' => 'control-label']) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::text('client_search', null, ['class' => 'form-control input-sm', 'id' => 'client_search', 'data-link' => 'function']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div id="client_search_results">
                @if(isset($client))
                    @include('client.partials._client_profile')
                @endif
            </div>

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Datos del Contrato</h4>
                    </div>
                </div>
                <div class="panel-body">

                    <div id="contract_articles">
                        @include('contract.partials._article_contract', ['default_article' => true])
                    </div>

                    <div class="form-group">
                        {{ Form::label('amount', 'Valor Contrato:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('amount', null, ['class' => 'form-control']) }}
                        </div>

                        {{ Form::label('payment', 'Prorroga:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-3">
                            {{ Form::text('payment', null, ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="form-group text-center">
                        {{ Form::submit('Guardar Contrato', ['class' => 'btn btn-primary']) }}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>T&eacute;rminos del contrato</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {{ Form::label('months', 'Nro Meses:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('months', 4, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('percentage', 'Porcentaje:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('percentage', 10, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{ Form::close() }}

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/contract.js') }}"></script>
    <script src="{{ public_assets('js/client_search.js') }}"></script>
@endsection