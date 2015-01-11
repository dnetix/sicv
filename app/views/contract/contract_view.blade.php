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

            <div id="client_results">
            @include('client.partials._client_profile', ['noChange' => true])
            </div>

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">Datos del Contrato</div>
                </div>
                <div class="panel-body">

                    <div id="contract_articles">
                        <h5>Articulo(s)</h5>
                        @foreach($articles as $article)
                            @include('contract.partials._article_contract', ['article' => $article])
                        @endforeach
                    </div>

                    <div class="form-group">
                        {{ Form::label('monthsTranscurred', 'Nro Meses', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-4">
                            {{ Form::text('monthsTranscurred', $contract->present()->elapsedMonths(), ['class' => 'form-control']) }}
                        </div>

                        {{ Form::label('payment', 'Prorroga:', ['class' => 'control-label col-sm-2']) }}
                        <div class="col-sm-3">
                            {{ Form::text('payment', $contract->present()->amountToTerminate(), ['class' => 'form-control', 'disabled' => 'disabled']) }}
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

            @if($contract->isActive())
            {{ Form::open(['class' => 'form-horizontal', 'route' => 'contract.extension', 'onsubmit' => 'return validateExtension();']) }}
            <div class="panel panel-warning extensions">
                <div class="panel-heading">
                    <div class="panel-title">Abonos [{{ $contract->present()->totalExtensions() }}]</div>
                </div>
                <div class="panel-body">
                    @forelse($extensions as $extension)
                        <div class="row extension">
                            <div class="col-sm-4 text-center">{{ $extension->present()->amount() }}</div>
                            <div class="col-sm-5 text-center">{{ $extension->present()->createdAt() }}</div>
                            <div class="col-sm-3 text-center"><a href="" class="btn btn-danger btn-xs fa fa-times"></a></div>
                        </div>
                    @empty
                        <h4>No se han realizado abonos</h4>
                    @endforelse
                    <hr />
                    <div class="form-group">
                        {{ Form::label('amount', 'Valor:', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-8">
                            {{ Form::text('amount', null, ['class' => 'form-control money', 'autocomplete' => 'off']) }}
                        </div>
                    </div>
                    <div class="form-group text-center">
                        {{ Form::hidden('contract_id', $contract->id()) }}
                        {{ Form::submit('Guardar', ['class' => 'btn btn-primary']) }}
                        {{ Form::input('button', null, 'Total', ['class' => 'btn btn-warning', 'onclick' => "setAmountExtension({$contract->duedExtensions()})"]) }}
                    </div>
                </div>
            </div>
            {{ Form::close() }}
            @endif

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">T&eacute;rminos del contrato</div>
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
    <script src="{{ public_assets('js/clients.js') }}"></script>
@endsection