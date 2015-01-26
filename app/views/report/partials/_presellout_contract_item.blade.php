<tr id="contract_id_{{ $contract->id() }}">
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
        <div class="text-center">{{ $contract->present()->monthStatistics(true) }}</div>
        <p class="text-center">Abonado: {{ $contract->present()->payedExtensions() }}</p>
    </td>
    <td>
        {{ $contract->present()->lastExtensionDate() }}
        <p><small>{{ $contract->present()->lastExtensionDateDiff() }}</small></p>
    </td>
    @if(!isset($nochange))
    <td class="hidden-print">
        <div class="ckbox ckbox-warning">
            <input type="checkbox" {{ isset($remove) ? 'data-remove="true"' : '' }}{{ isset($kindStatistics) ? 'data-kind="'.$kindStatistics.'"' : '' }} name="preSellout[]" value="{{ $contract->id() }}" onchange="togglePreSellout({{ $contract->id() }}, this)" id="preSellout_{{ $contract->id() }}" {{ $contract->isPreSellout() ? ' checked="checked"' : '' }}>
            <label for="preSellout_{{ $contract->id() }}"></label>
        </div>
    </td>
    @endif
</tr>