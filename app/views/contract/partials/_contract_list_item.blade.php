<tr class="contract">
    <td class="contract-id"><a href="{{ route('contract.view', $contract->id()) }}">{{ $contract->id() }}</a></td>
    <td>
        @if(!isset($isClient))
        <p><a href="{{ route('client.view', $contract->clientId()) }}">{{ $contract->present()->clientName() }}</a></p>
        @endif
        <p>{{ $contract->present()->articlesNames() }}</p>
    </td>
    <td class="amount">{{ $contract->present()->amount() }}</td>
    <td class="option">
        <div class="contract-label label-{{ $contract->state() }}">{{ $contract->present()->state() }}</div>
    </td>
</tr>