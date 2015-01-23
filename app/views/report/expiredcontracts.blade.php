@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="glyphicon glyphicon-list"></i> Contratos Vencidos</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="expired-contracts">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Informaci&oacute;n de Contrato</th>
                    <th>Valor</th>
                    <th>Fecha</th>
                    <th>En Meses</th>
                    <th>&Uacute;ltimo Abono</th>
                    <th>Presaca</th>
                </tr>
            </thead>
            <tbody>
        @forelse($contracts as $contract)
            <tr>
                <td>
                    <a href="{{ route('contract.view', $contract->id()) }}">{{ $contract->present()->id() }}</a>
                </td>
                <td>
                    <a href="{{ route('client.view', $contract->clientId()) }}">{{ $contract->present()->clientName() }}</a>
                    <p><small>{{ $contract->present()->clientContactInfo() }}</small></p>
                    <p>{{ $contract->present()->articlesNames() }}</p>
                </td>
                <td class="amount">
                    <span>{{ $contract->present()->amount() }}</span>
                    <p><small>({{ $contract->present()->percentage() }})</small></p>
                </td>
                <td>
                    {{ $contract->present()->shortCreatedAt() }}
                </td>
                <td class="month-statistics">
                    {{ $contract->present()->monthStatistics(true) }}
                </td>
                <td>
                    {{ $contract->present()->lastExtensionDate() }}
                    <p><small>{{ $contract->present()->lastExtensionDateDiff() }}</small></p>
                </td>
                <td>
                    <div class="ckbox ckbox-warning">
                        <input type="checkbox" name="preSellout[]" value="{{ $contract->id() }}" onchange="togglePreSellout({{ $contract->id() }}, this)" id="preSellout_{{ $contract->id() }}" {{ $contract->isPreSellout() ? ' checked="checked"' : '' }}>
                        <label for="preSellout_{{ $contract->id() }}"></label>
                    </div>
                </td>
            </tr>
        @empty

        @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- contentpanel -->
@endsection

@section('js')
    <script src="{{ public_assets('js/utils.js') }}"></script>
@endsection