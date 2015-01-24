@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-edit"></i> Nuevo Contrato</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
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
                            {{ Form::text('client_search', null, ['class' => 'form-control input-sm', 'id' => 'client_search', 'data-link' => 'function', 'placeholder' => 'Busqueda por nombre o cedula']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div id="client_results">
                @if(isset($client))
                    @include('client.partials._client_profile')
                @endif
            </div>

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">Datos del Contrato</div>
                </div>
                <div class="panel-body">

                    <div id="contract_articles">
                        @if(isset($articles))
                            @foreach($articles as $article)
                                @include('contract.partials._article_contract_new')
                            @endforeach
                        @else
                            @include('contract.partials._article_contract_new', ['default_article' => true])
                        @endif
                    </div>

                    <div class="form-group">
                        {{ Form::label('amount', 'Valor Contrato:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('amount', (isset($contract) ? $contract->present()->amount() : null), ['class' => 'form-control money', 'readonly' => 'readonly', 'id' => 'contract_amount']) }}
                        </div>

                        {{ Form::label('payment', 'Prorroga:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-3">
                            {{ Form::text('payment', (isset($contract) ? $contract->present()->payment() : null), ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="form-group text-center">
                        {{ Form::submit('Guardar Contrato', ['class' => 'btn btn-primary btn-block btn-lg']) }}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">T&eacute;rminos del contrato</div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {{ Form::label('months', 'Nro Meses:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('months', (isset($contract) ? $contract->months() : $default_months), ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('percentage', 'Porcentaje:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('percentage', (isset($contract) ? $contract->percentage() + 0 : $default_percentage), ['class' => 'form-control percent', 'autocomplete' => 'off']) }}
                        </div>
                    </div>
                </div>
            </div>

            <div id="client-notes-wrapper"></div>
        </div>

    </div>
    {{ Form::close() }}

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/contract.js') }}"></script>
    <script src="{{ public_assets('js/clients.js') }}"></script>
@endsection