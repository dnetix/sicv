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

    {{ Form::open(['class' => 'form-horizontal', 'route' => 'contract.store']) }}
    @include('layouts.partials._errors')
    <div class="row">
        <div class="col-md-9">

            <div class="panel panel-success" id="client_search_panel">
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

            <div id="client_search_results"></div>

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Datos del articulo</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {{ Form::label('article', 'Articulo:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('article', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('articletype', 'Tipo Articulo:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::select('price', [1 => 'Oro'], null, ['class' => 'form-control']) }}
                        </div>

                        {{ Form::label('weight', 'Peso:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-2">
                            {{ Form::text('weight', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('price', 'Valor:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('price', null, ['class' => 'form-control']) }}
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
                            {{ Form::text('months', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('percent', 'Porcentaje:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('percent', null, ['class' => 'form-control']) }}
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
    <script src="{{ public_assets('js/client_search.js') }}"></script>
@endsection