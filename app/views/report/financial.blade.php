@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Reporte Financiero</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="panel-title">Reporte Financiero</div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h2>Ingresos</h2>
                            <div class="stat">
                                <small>Pagado en Abonos</small>
                                <h4>{{ $financial->present()->totalExtensions() }}</h4>
                            </div>
                            <div class="stat">
                                <small>Cancelado de contratos</small>
                                <h4>{{ $financial->present()->totalContractsTerminations() }}</h4>
                                <p class="text-muted">{{ $financial->present()->totalExtensionsFromEndAmounts() }} de ganancia</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h2>Egresos</h2>

                            <div class="stat">
                                <small>Prestado en Contratos</small>
                                <h4>{{ $financial->present()->totalContractsAmount() }}</h4>
                            </div>

                            <div class="stat">
                                <small>Gastos</small>
                                <h4>{{ $financial->present()->totalExpenses() }}</h4>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Opciones de reporte</div>
                </div>
                <div class="panel-body">
                    {{ Form::open(['route' => 'report.financial', 'method' => 'get', 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        {{ Form::label('startDate', 'Inicio', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" value="{{ $financial->present()->startDate() }}" class="form-control datepicker" placeholder="yyyy-mm-dd" id="startDate" name="startDate">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('endDate', 'Final', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            <div class="input-group">
                                <input type="text" value="{{ $financial->present()->endDate() }}" class="form-control datepicker" placeholder="yyyy-mm-dd" id="endDate" name="endDate">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center">
                        {{ Form::submit('Crear Reporte', ['class' => 'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
    <script src="{{ public_assets('bracket/js/jquery-ui-1.10.3.min.js') }}"></script>
    <script src="{{ public_assets('bracket/js/lang/datepicker-es.js') }}"></script>
    <script src="{{ public_assets('js/utils.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".datepicker").each(function(){
                $(this).datepicker($.datepicker.regional['es']);
            });
        });
    </script>
@endsection