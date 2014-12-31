@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-edit"></i> Nuevo Contrato</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ date_print_format(date('Y-m-d')) }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        {{ Form::open(['class' => 'form-horizontal']) }}

            <div class="col-md-8">

                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="form-group">
                            {{ Form::label('q_client', 'Buscar Cliente:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('q_client', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="results-list">
                            <a href="hola">
                                <div class="media">
                                    <div class="pull-left">
                                        <img alt="" src="{{ public_assets('images/profile/user.png') }}">
                                    </div>
                                    <div class="media-body">
                                        <span class="media-meta pull-right">1040035062</span>
                                        <h4 class="text-primary">Diego Arturo Calle</h4>
                                        <p class="email-summary"><strong>Telefonos:</strong> 5533112 - 3006108399</p>
                                    </div>
                                </div>
                            </a>
                            <div class="media">
                                <a href="#" class="pull-left">
                                    <img alt="" src="{{ public_assets('images/profile/user.png') }}">
                                </a>
                                <div class="media-body">
                                    <span class="media-meta pull-right">1040035062</span>
                                    <h4 class="text-primary">Diego Arturo Calle</h4>
                                    <p class="email-summary"><strong>Telefonos:</strong> 5533112 - 3006108399</p>
                                </div>
                            </div>
                            <div class="media">
                                <a href="#" class="pull-left">
                                    <img alt="" src="{{ public_assets('images/profile/user.png') }}">
                                </a>
                                <div class="media-body">
                                    <span class="media-meta pull-right">1040035062</span>
                                    <h4 class="text-primary">Diego Arturo Calle</h4>
                                    <p class="email-summary"><strong>Telefonos:</strong> 5533112 - 3006108399</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

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

            <div class="col-md-4">
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

        {{ Form::close() }}

    </div>

</div>
<!-- contentpanel -->
@endsection