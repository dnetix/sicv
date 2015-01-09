<tr>
    <td><a href="#">{{ $contract->getId() }}</a></td>
    <td>
        @if(!isset($isClient))
        <p><a href="{{ route('client.view', $contract->getClientId()) }}">{{ $contract->present()->getClientName() }}</a></p>
        @endif
        <p>{{ $contract->present()->getArticlesNames() }}</p>
    </td>
    <td>{{ $contract->present()->getAmount() }}</td>
</tr>