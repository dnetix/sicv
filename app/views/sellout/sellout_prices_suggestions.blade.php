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
                        <td>
                            {{ Form::text('buy_price['.$suggestion->pivotId().']', $suggestion->present()->buyPrice(), ['class' => 'form-control money']) }}
                        </td>
                        <td>
                            {{ Form::text('sell_price['.$suggestion->pivotId().']', $suggestion->present()->sellPrice(), ['class' => 'form-control money']) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection