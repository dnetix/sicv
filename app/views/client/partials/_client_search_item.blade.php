<tr>
    <td>
        @if($link == "link")
        <a style="display: block;" href="{{ route('client.view', ['id' => $client->id()]) }}">
        @elseif($link == "function")
        <a href="javascript:void(0)" onclick="clientSelected({{ $client->id() }})">
        @endif
            <div class="media">
                <div class="media-body">
                    <span class="media-meta pull-right">{{ $client->idNumber() }}</span>
                    <h4 class="text-primary">{{ $client->name() }}</h4>
                    <small class="text-muted"></small>
                    <p class="email-summary"><strong>{{ $client->phoneNumber() }}</strong> {{ $client->address() }}</p>
                </div>
            </div>
        </a>
    </td>
</tr>