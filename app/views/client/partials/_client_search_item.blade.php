<tr>
    <td>
        @if($link == "link")
        <a style="display: block;" href="{{ route('client.view', ['id' => $client->getId()]) }}">
        @elseif($link == "function")
        <a href="javascript:void(0)" onclick="clientSelected({{ $client->getId() }})">
        @endif
            <div class="media">
                <div class="media-body">
                    <span class="media-meta pull-right">{{ $client->getIdNumber() }}</span>
                    <h4 class="text-primary">{{ $client->getName() }}</h4>
                    <small class="text-muted"></small>
                    <p class="email-summary"><strong>{{ $client->getPhoneNumber() }}</strong> {{ $client->getAddress() }}</p>
                </div>
            </div>
        </a>
    </td>
</tr>