@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Tipos de Articulos</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-7">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">Tipos de Gasto</div>
                </div>
                <div class="panel-body">
                    <ul>
                    @foreach($expenseTypes as $expenseType)
                        <li>{{ $expenseType->name() }} <a href="javascript:void(0)" onclick="editThis({{ $expenseType->id() }})">Editar</a></li>
                    @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">Crear o Editar Tipo Gasto</div>
                </div>
                <div class="panel-body">
                    {{ Form::open(['route' => 'budget.saveexpensetype', 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        {{ Form::label('name', 'Nombre', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                    </div>

                    <div class="form-group text-center">
                        {{ Form::hidden('id', null, ['id' => 'id']) }}
                        {{ Form::submit('Crear Nuevo', ['class' => 'btn btn-primary', 'id' => 'btn_submit']) }}
                        <input type="button" value="Cancelar" class="btn btn-default" onclick="cancelNode()">
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('js')
    <script>
        function editThis(id){
            $.ajax({
                url: SITE_BASE + "budget/expensetype",
                type: "get",
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $("#id").val(data.id);
                    $("#name").val(data.name);
                    $("#btn_submit").val("Editar Tipo Gasto");
                }
            });
        }

        function cancelNode(){
            $("#id").val("");
            $("#name").val("");
            $("#btn_submit").val("Crear Nuevo");
        }
    </script>
@endsection