<div class="panel panel-{{ $client->isFlagged() ? 'danger' : 'sucess' }}">
    <div class="panel-heading">

        <div class="panel-btns">
            <a href="javascript:void(0)" onclick="openClientEditPanel({{ $client->id() }})"><i class="fa fa-edit"></i> Editar</a>
            @if(!isset($noChange))
            <a href="javascript:void(0)" onclick="openClientSearchPanel()"><i class="fa fa-arrow-circle-left"></i> Cambiar</a>
            @endif
        </div>

        <div class="panel-title">Datos del cliente</div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-7">
                <h4 class="person-name"><a href="{{ route('client.view', $client->id()) }}">{{ $client->present()->name() }}</a></h4>
                <div class="text-muted">{{ $client->present()->phones() }}</div>
                <div class="text-muted">{{ $client->present()->address() }}</div>
            </div>
            <div class="col-md-5">
                <div class="pull-right text-right">
                    <h5 class="person-identification">{{ $client->present()->identification() }}</h5>
                    <div class="text-muted">{{ $client->present()->idExpedition() }}</div>
                </div>
            </div>
        </div>
        {{ Form::hidden('client_id', $client->id(), ['id' => 'client_id']) }}
    </div>
</div>