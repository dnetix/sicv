@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-edit"></i> Contrato {{ $contract->id() }}</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    @include('layouts.partials._errors')
    <div class="row">
        <div class="col-md-9">

            @include('client.partials._client_profile', ['noChange' => true])

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>Datos del Contrato</h4>
                    </div>
                </div>
                <div class="panel-body">

                    <div id="contract_articles">
                        @foreach($articles as $article)
                        <div class="article_fields">
                            <div class="form-group">
                                {{ Form::label('article_description', 'Articulo:', ['class' => 'control-label col-sm-2']) }}
                                <div class="col-sm-10">
                                    {{ Form::text('article[]', $article->description(), ['class' => 'form-control', 'id' => 'article_description']) }}
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('article_type_id[]', 'Tipo Articulo:', ['class' => 'control-label col-sm-2']) }}
                                <div class="col-sm-4">
                                    {{ Form::text('article_type_id[]', $article->present()->articleType(), ['class' => 'form-control', 'id' => 'article_description']) }}
                                </div>

                                {{ Form::label('weight[]', 'Peso:', ['class' => 'control-label col-sm-2']) }}
                                <div class="col-sm-2">
                                    {{ Form::text('weight[]', $article->weight(), ['class' => 'form-control', 'id' => 'weight', 'placeholder' => 'gramos']) }}
                                </div>
                                <div class="col-sm-2 text-right">
                                    <input type="button" tabindex="-1" class="btn btn-primary" onclick="addArticleFieldsContract()" value="+">
                                </div>
                            </div>
                            <hr />
                        </div>
                        @endforeach
                    </div>

                    <div class="form-group">
                        {{ Form::label('monthsTranscurred', 'Nro Meses', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('monthsTranscurred', $contract->present()->elapsedMonths(), ['class' => 'form-control']) }}
                        </div>

                        {{ Form::label('payment', 'Prorroga:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-3">
                            {{ Form::text('payment', $contract->present()->payment(), ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('amount', 'Valor Contrato:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('amount', $contract->present()->amount(), ['class' => 'form-control']) }}
                        </div>

                        {{ Form::label('payment', 'Prorroga:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-3">
                            {{ Form::text('payment', $contract->present()->payment(), ['class' => 'form-control', 'disabled' => 'disabled']) }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-3">
            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h4>T&eacute;rminos del contrato</h4>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        {{ Form::label('months', 'Nro Meses:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('months', 4, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('percentage', 'Porcentaje:', ['class' => 'control-label col-sm-6']) }}
                        <div class="col-sm-3">
                            {{ Form::text('percentage', 10, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/contract.js') }}"></script>
    <script src="{{ public_assets('js/client_search.js') }}"></script>
@endsection