@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-user"></i> Detalles de cliente</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-9 col-sm-8 col-xs-12">

            {{ Form::open(['class' => 'form-horizontal', 'route' => ['client.edit', $client->id()]]) }}
                <div class="panel panel-success">
                    <div class="panel-heading">
                        <div class="panel-btns">
                            <a href="#" class="minimize">−</a>
                        </div>
                        <div class="panel-title">Datos de Cliente</div>
                    </div>
                    <div class="panel-body">

                        @include('layouts.partials._errors')

                        <div class="form-group">
                            {{ Form::label('name', 'Nombre:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-10">
                                {{ Form::text('name', $client->name(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('id_number', 'Identificaci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-2">
                                {{ Form::select('id_type', ['CC' => 'CC'], $client->idType(), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-5">
                                {{ Form::text('id_number', $client->idNumber(), ['class' => 'form-control', 'placeholder' => 'Nro identificaci&oacute;n']) }}
                            </div>
                            <div class="col-sm-3">
                                {{ Form::text('id_expedition', $client->idExpedition(), ['class' => 'form-control', 'placeholder' => 'Lugar Expedici&oacute;n']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('cell_number', 'Celular:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('cell_number', $client->cellNumber(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('phone_number', 'Telefono:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-5">
                                {{ Form::text('phone_number', $client->phoneNumber(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            {{ Form::label('address', 'Direcci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
                            <div class="col-sm-9">
                                {{ Form::text('address', $client->address(), ['class' => 'form-control']) }}
                            </div>
                        </div>


                    </div><!-- panel-body -->
                    <div class="panel-footer text-center">
                        <button class="btn btn-primary">Editar datos</button>
                    </div><!-- panel-footer -->
                </div>
            {{ Form::close() }}

            @include('contract.partials._contracts_client_panel')

        </div>

        <div class="col-md-3">

            <div class="panel panel-dark panel-stat">
                <a href="{{ route('contract.new', $client->id()) }}">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-4">
                                <img src="{{ public_assets('images/template/is-document.png') }}" alt="" />
                            </div>
                            <div class="col-xs-8">
                                <small class="stat-label">Nuevo</small>
                                <h1>Contrato</h1>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            @include('client.partials._client_notes')

            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="panel-title">Marcar cliente</div>
                </div>
                <div class="panel-body">
                    <p>Puede marcar un cliente para que sea notado cada vez que se busca o que se realiza un contrato y as&iacute; forzar a leer informacion relevante.</p>
                    {{ Form::open(['route' => 'client.toggleflag', 'class' => 'form']) }}
                    <div class="form-group">
                        {{ Form::hidden('client_id', $client->id()) }}
                        @if($client->isFlagged())
                            {{ Form::submit('Desmarcar Cliente', ['class' => 'btn btn-primary btn-block']) }}
                        @else
                            {{ Form::submit('Marcar Cliente', ['class' => 'btn btn-danger btn-block']) }}
                        @endif
                    </div>
                    {{ Form::close() }}
                </div>
            </div>

        </div>

    </div>

</div>
<!-- contentpanel -->
@endsection

@section('js')
    <script src="{{ public_assets('js/clients.js') }}"></script>
@endsection