<tr class="contract">
    <td class="contract-id"><a href="{{ route('contract.view', $contract->id()) }}">{{ $contract->id() }}</a></td>
    <td>
        <p>{{ $contract->present()->articlesNames() }}</p>
    </td>
    <td class="amount">{{ $contract->present()->amount() }}</td>
    <td class="option">
        <div class="contract-label label-{{ $contract->state() }}">{{ $contract->present()->state() }}</div>
    </td>
    <td>
        @if(!$contract->isActive())
        <a href="{{ route('contract.clone', $contract->id()) }}" class="fa fa-copy btn btn-default"></a>
        @endif
    </td>
</tr>