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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Tipos de Articulo</div>
                </div>
                <div class="panel-body">
                    {{ $articleTypes->asOrderedList() }}
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="panel-title">Crear o Editar Tipo Articulo</div>
                </div>
                <div class="panel-body">
                    {{ Form::open(['route' => 'article.savetype', 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        {{ Form::label('name', 'Nombre', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('parent_id', 'Tipo Superior', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-9">
                            {{ $articleTypes->asHTMLSelect('parent_id', null, ['class' => 'form-control', 'id' => 'parent_id'], 'Sin tipo superior') }}
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
        $(document).ready(function(){
            $('.node').each(function(){
                var node = $(this);
                node.html(node.html() + " <a href='javascript:void(0)' onclick='editNode(" + node.data("id") + ")'>Editar</a>");
            });
        });

        function editNode(nodeId){
            $.ajax({
                url: SITE_BASE + "article/type",
                type: "get",
                dataType: "json",
                data: {
                    id: nodeId
                },
                success: function (data) {
                    $("#id").val(data.id);
                    $("#name").val(data.article_type);
                    $("#parent_id").val(data.article_type_id);
                    $("#btn_submit").val("Editar Tipo Articulo");
                }
            });
        }

        function cancelNode(){
            $("#id").val("");
            $("#name").val("");
            $("#parent_id").val("");
            $("#btn_submit").val("Crear Nuevo");
        }
    </script>
@endsection