@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Asignacion de Valores a los Articulos</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="panel panel-warning">
        <div class="panel-heading">
            <div class="panel-title">Operaciones de saca</div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group form-horizontal">
                        {{ Form::label('op_sell', 'Reducir precios de venta (%)', ['class' => 'control-label col-sm-4']) }}
                        <div class="col-sm-2">
                            <input type="text" id="op_sell" class="form-control " placeholder="%" />
                        </div>
                        <div class="col-sm-3">
                            <input type="button" class="btn btn-darkblue" value="Cambiar" onclick="decreaseByPercentage('.sell_price input', $('#op_sell').val())" />
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <p>Notas de la saca</p>
                        <textarea id="show_note" class="form-control"></textarea>
                    </div>
                    <div class="form-group text-center">
                        <input type="button" class="btn btn-danger btn-lg" value="Realizar Saca" onclick="createSellout()">
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{ Form::open(['route' => 'sellout.create', 'class' => 'form-horizontal', 'id' => 'frm_sellout']) }}
        <div class="table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Contrato</th>
                        <th>Articulo Contrato</th>
                        <th>Valor Contrato</th>
                        <th>Valor Compra</th>
                        <th>Precio Venta</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($suggestions as $suggestion)
                    <tr>
                        <td>{{ $suggestion->present()->contractId() }}</td>
                        <td>{{ $suggestion->present()->articleDescription() }}</td>
                        <td>{{ $suggestion->present()->contractAmount() }}</td>
                        <td class="buy_price">{{ $suggestion->present()->buyPrice() }}</td>
                        <td class="sell_price">
                            {{ Form::text('sell_price['.$suggestion->pivotId().']', $suggestion->present()->sellPrice(), ['class' => 'form-control money']) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
    <div class="hidden">
        {{ Form::textarea('note', null, ['id' => 'note']) }}
    </div>
    {{ Form::close() }}

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/sellout.js') }}"></script>
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection