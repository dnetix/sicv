<tr{{ $client->isFlagged() ? ' class="flagged"' : '' }}>
    <td>
        @if($link == "link")
        <a style="display: block;" href="{{ route('client.view', ['id' => $client->id()]) }}">
        @elseif($link == "function")
        <a href="javascript:void(0)" onclick="clientSelected({{ $client->id() }})">
        @endif
            <div class="media">
                <div class="media-body">
                    <span class="media-meta pull-right">{{ $client->present()->idNumber() }}</span>
                    <h4 class="text-primary">{{ $client->present()->name() }}</h4>
                    <small class="text-muted"></small>
                    <p class="email-summary"><strong>{{ $client->present()->phoneNumber() }}</strong> {{ $client->present()->address() }}</p>
                </div>
            </div>
        </a>
    </td>
</tr>