@extends('layouts.default')

@section('content')
<div class="pageheader">
    <h2><i class="glyphicon glyphicon-cog"></i> Configuraci&oacute;n de Usuario</h2>
    <div class="breadcrumb-wrapper">
        <span class="label">{{ date_print_format(date('Y-m-d')) }}</span>
    </div>
</div>

<div class="contentpanel">

    <div class="row">
        <div class="col-md-8 col-sm-8 col-xs-12">

            {{ Form::open(['class' => 'form-horizontal']) }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="#" class="panel-close">×</a>
                            <a href="#" class="minimize">−</a>
                        </div>
                        <h4 class="panel-title">Datos de Usuario</h4>
                    </div>
                    <div class="panel-body">

                        <div class="form-group">
                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('idnumber', 'Identificaci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-2">
                                {{ Form::select('idtype', ['CC' => 'CC'], null, ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-5">
                                {{ Form::text('idnumber', null, ['class' => 'form-control', 'placeholder' => 'Nro identificaci&oacute;n']) }}
                            </div>
                            <div class="col-sm-3">
                                {{ Form::text('expedition', null, ['class' => 'form-control', 'placeholder' => 'Lugar Expedici&oacute;n']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('email', 'Email:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-8">
                                {{ Form::text('email', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('cellnumber', 'Celular:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('cellnumber', null, ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('telephone', 'Telefono:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('telephone', null, ['class' => 'form-control']) }}
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

        <div class="col-md-4">
            {{ Form::open(['class' => 'form-horizontal']) }}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="#" class="panel-close">×</a>
                            <a href="#" class="minimize">−</a>
                        </div>
                        <h4 class="panel-title">Contrase&ntilde;a</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            {{ Form::label('oldpassword', 'Contrase&ntilde;a:', ['class' => 'control-label col-sm-3']) }}
                            <div class="col-sm-9">
                                {{ Form::password('oldpassword', ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            {{ Form::label('password', 'Nueva:', ['class' => 'control-label col-sm-3']) }}
                            <div class="col-sm-9">
                                {{ Form::password('password', ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('password_confirmation', 'Confirmar:', ['class' => 'control-label col-sm-3']) }}
                            <div class="col-sm-9">
                                {{ Form::password('password_confirmation', ['class' => 'form-control']) }}
                            </div>
                        </div>

                    </div>
                    <div class="panel-footer text-center">
                        <button class="btn btn-primary">Cambiar</button>
                    </div><!-- panel-footer -->
                </div>
            {{ Form::close() }}

            {{ Form::open(['class' => 'form-horizontal']) }}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-btns">
                        <a href="#" class="panel-close">×</a>
                        <a href="#" class="minimize">−</a>
                    </div>
                    <h4 class="panel-title">Nombre de Usuario</h4>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {{ Form::label('username', 'Usuario:', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ Form::text('username', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <button class="btn btn-primary">Cambiar</button>
                </div><!-- panel-footer -->
            </div>
            {{ Form::close() }}

        </div>

    </div>

</div>
<!-- contentpanel -->
@endsection