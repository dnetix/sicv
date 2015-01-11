@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-user"></i> Nuevo cliente</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-9 col-sm-8 col-xs-12">

            {{ Form::open(['class' => 'form-horizontal', 'route' => 'client.store']) }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="#" class="panel-close">×</a>
                            <a href="#" class="minimize">−</a>
                        </div>
                        <div class="panel-title">Nuevo Cliente</div>
                    </div>
                    <div class="panel-body">

                        @include('layouts.partials._errors')

                        <div class="form-group">
                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('id_number', 'Identificaci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-2">
                                {{ Form::select('id_type', ['CC' => 'CC'], null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-5">
                                {{ Form::text('id_number', null, ['class' => 'form-control', 'placeholder' => 'Nro identificaci&oacute;n']) }}
                            </div>
                            <div class="col-sm-3">
                                {{ Form::text('id_expedition', null, ['class' => 'form-control', 'placeholder' => 'Lugar Expedici&oacute;n']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('cell_number', 'Celular:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('cell_number', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('phone_number', 'Telefono:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('phone_number', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('address', 'Direcci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-9">
                                {{ Form::text('address', null, ['class' => 'form-control']) }}
                            </div>
                        </div>


                    </div><!-- panel-body -->
                    <div class="panel-footer text-center">
                        <button class="btn btn-primary">Guardar Cliente</button>
                        <button type="reset" class="btn btn-default">Cancelar</button>
                    </div><!-- panel-footer -->
                </div>
            {{ Form::close() }}

        </div>

    </div>

</div>
<!-- contentpanel -->
@endsection