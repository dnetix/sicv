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

            <div class="panel panel-contract-options contract-{{ $contract->state() }}">
                <div class="panel-heading">
                    <div class="panel-title">Contrato {{ $contract->present()->state() }} {{ $contract->isPreSellout() ? '<span class="label label-danger presellout">En Presaca</span>' : '' }}</div>
                </div>
                <div class="panel-body">
                    {{-- //TODO views for all states --}}
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="stat">
                                <small>Fecha creaci&oacute;n</small>
                                <h3>{{ $contract->present()->createdAt() }}</h3>
                                <div class="text-muted">{{ $contract->present()->elapsedSinceCreated() }}</div>
                            </div>
                        </div>
                        <div class="col-sm-4 col-sm-offset-1 amount">
                            <div class="stat">
                                <small>Valor contrato</small>
                                <h3>{{ $contract->present()->amount() }}</h3>
                            </div>
                            <div class="stat">
                                <small>Pago mes</small>
                                <h5>{{ $contract->present()->payment() }} <strong>({{ $contract->present()->percentage() }})</strong></h5>
                            </div>
                        </div>
                    </div>
                    <hr />

                    {{-- //TODO options for active state --}}
                    @if($contract->isActive())
                        @include('contract.partials.displays._active')
                    @elseif($contract->isTerminated())
                        @include('contract.partials.displays._terminated')
                    @elseif($contract->isEnded())
                        @include('contract.partials.displays._ended')
                    @elseif($contract->isAnnulled())
                        @include('contract.partials.displays._annulled')
                    @endif
                </div>
            </div>

            <div class="panel panel-dark">
                <div class="panel-heading">
                    <div class="panel-title">Articulo(s)</div>
                </div>
                <div class="panel-body">

                    <div id="contract_articles">
                        @foreach($articles as $article)
                            @include('contract.partials._article_contract', ['article' => $article])
                        @endforeach
                    </div>

                </div>
            </div>

        </div>

        <div class="col-md-3">

            @if($contract->isActive())
            {{ Form::open(['class' => 'form-horizontal', 'route' => 'contract.extension', 'onsubmit' => 'return validateExtension();']) }}
            <div class="panel panel-warning extensions">
                <div class="panel-heading">
                    <div class="panel-title">Abonos [{{ $contract->present()->payedExtensions() }}]</div>
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
                    @if($contract->isPreSellout())
                        <div class="form-group text-center presellout">
                            <input type="button" class="btn btn-danger" value="Remover de la Presaca" onclick="removePreSellout({{ $contract->id() }}, this)" />
                            <hr />
                        </div>
                    @endif
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

            @include('client.partials._client_notes')

        </div>

    </div>

</div>
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
    <script src="{{ public_assets('js/contract.js') }}"></script>
    <script src="{{ public_assets('js/clients.js') }}"></script>
@endsection