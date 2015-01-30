@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-usd"></i> Gastos</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="panel-title">Nuevo Gasto</div>
                </div>
                <div class="panel-body">
                    @include('layouts.partials._errors')
                    {{ Form::open(['class' => 'form-horizontal', 'route' => 'budget.newexpense']) }}
                    <div class="form-group">
                        {{ Form::label('amount', 'Valor', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::text('amount', null, ['class' => 'form-control money']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            {{ Form::label('description', 'Descripci&oacute;n', ['class' => 'control-label']) }}
                        </div>
                        <div class="col-sm-12">
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '4']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('expense_type_id', 'Tipo', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-10">
                            {{ Form::select('expense_type_id', $expenseTypes, null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group text-center">
                        {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="panel-title">Gastos</div>
                </div>
                <div class="panel-body">

                    <div class="mb20">
                        {{ Form::open(['route' => 'budget.expenses', 'method' => 'get', 'class' => 'form-horizontal']) }}
                            <div class="form-group">
                                {{ Form::label('startDate', 'Inicio', ['class' => 'control-label col-sm-1 col-sm-offset-1']) }}
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" value="{{ $startDate }}" class="form-control datepicker" placeholder="yyyy-mm-dd" id="startDate" name="startDate">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                {{ Form::label('endDate', 'Final', ['class' => 'control-label col-sm-1']) }}
                                <div class="col-sm-2">
                                    <div class="input-group">
                                        <input type="text" value="{{ $endDate }}" class="form-control datepicker" placeholder="yyyy-mm-dd" id="endDate" name="endDate">
                                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 text-center">{{ Form::submit('Ver gastos', ['class' => 'btn btn-primary']) }}</div>
                            </div>
                        {{ Form::close() }}
                        <hr />
                    </div>

                    <div class="list_expenses">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Valor</th>
                                    <th>Descripci&oacute;n</th>
                                    <th>Tipo</th>
                                    <th>Usuario</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($expenses as $expense)
                                <tr>
                                    <td>{{ $expense->present()->createdAt() }}</td>
                                    <td>{{ $expense->present()->amount() }}</td>
                                    <td>{{ $expense->present()->description() }}</td>
                                    <td>{{ $expense->present()->expenseType() }}</td>
                                    <td>{{ $expense->present()->user() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"><h3>No hay gastos en el rango indicado</h3></td>
                                </tr>
                            @endforelse
                            </tbody>
                            @if($totalExpenses > 0)
                                <tfoot>
                                    <tr>
                                        <th>Total</th>
                                        <th>$ {{ number_format($totalExpenses) }}</th>
                                        <th colspan="3">&nbsp;</th>
                                    </tr>
                                </tfoot>
                            @endif
                        </table>
                    </div>

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