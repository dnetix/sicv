<div class="panel panel-success">
    <div class="panel-heading">
        @if(!isset($noChange))
        <div class="panel-btns">
            <a href="javascript:void(0)" onclick="openClientSearchPanel()"><i class="fa fa-arrow-circle-left"></i> Cambiar</a>
        </div>
        @endif
        <h4 class="panel-title">Datos de Cliente</h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            {{ Form::label('name', 'Nombre:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-8">
                {{ Form::text('name', $client->name(), ['class' => 'form-control']) }}
            </div>
            <div class="col-sm-2">
                <a target="_blank" tabindex="-1" class="btn btn-warning btn-block" href="{{ route('client.view', $client->id()) }}">Historial</a>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('id_number', 'Identificaci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-2">
                {{ Form::select('id_type', ['CC' => 'CC'], $client->idType(), ['class' => 'form-control', 'readonly' => 'readonly']) }}
            </div>
            <div class="col-sm-5">
                {{ Form::text('id_number', $client->idNumber(), ['class' => 'form-control', 'placeholder' => 'Nro identificaci&oacute;n', 'readonly' => 'readonly']) }}
            </div>
            <div class="col-sm-3">
                {{ Form::text('id_expedition', $client->idExpedition(), ['class' => 'form-control', 'placeholder' => 'Lugar Expedici&oacute;n', 'readonly' => 'readonly']) }}
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

        {{ Form::hidden('client_id', $client->id(), ['id' => 'client_id']) }}

    </div>
</div>