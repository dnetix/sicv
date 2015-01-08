<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="javascript:void(0)" onclick="openClientSearchPanel()"><i class="fa fa-arrow-circle-left"></i> Cambiar</a>
        </div>
        <h4 class="panel-title">Datos de Cliente</h4>
    </div>
    <div class="panel-body">

        <div class="form-group">
            {{ Form::label('name', 'Nombre:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-10">
                {{ Form::text('name', $client->getName(), ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('id_number', 'Identificaci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-2">
                {{ Form::select('id_type', ['CC' => 'CC'], $client->getIdType(), ['class' => 'form-control']) }}
            </div>
            <div class="col-sm-5">
                {{ Form::text('id_number', $client->getIdNumber(), ['class' => 'form-control', 'placeholder' => 'Nro identificaci&oacute;n']) }}
            </div>
            <div class="col-sm-3">
                {{ Form::text('id_expedition', $client->getIdExpedition(), ['class' => 'form-control', 'placeholder' => 'Lugar Expedici&oacute;n']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('cellnumber', 'Celular:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-5">
                {{ Form::text('cellnumber', $client->getCellNumber(), ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('phonenumber', 'Telefono:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-5">
                {{ Form::text('phonenumber', $client->getPhoneNumber(), ['class' => 'form-control']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('address', 'Direcci&oacute;n:', ['class' => 'control-label col-sm-2']) }}
            <div class="col-sm-9">
                {{ Form::text('address', $client->getAddress(), ['class' => 'form-control']) }}
            </div>
        </div>

        {{ Form::hidden('client_id', $client->getId()) }}

    </div>
</div>