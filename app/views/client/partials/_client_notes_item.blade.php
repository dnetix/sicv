<div class="client-note {{ $clientNote->importance() }}">
    <div class="note-header">
        <h5>{{ $clientNote->present()->author() }}</h5>
        <div class="text-muted text-right">{{ $clientNote->present()->createdAt() }}</div>
    </div>
    <div class="body">
        {{ $clientNote->present()->note() }}
    </div>
</div>