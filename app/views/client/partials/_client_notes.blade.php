<div id="client-notes" class="panel panel-default">
    <div class="panel-heading">
        <div class="panel-title">Notas de cliente</div>
    </div>
    <div class="panel-body">
        <div id="client-notes-list" class="client-notes-list">
            @forelse($clientNotes as $clientNote)
                @include('client.partials._client_notes_item')
            @empty
                <h4 class="no-result text-center">El cliente no tiene notas</h4>
            @endforelse
        </div>
        @if(!isset($viewOnly))
        {{ Form::open(['route' => 'client.note', 'class' => 'form']) }}
        <div class="form-group">
            {{ Form::label('note', 'Nota', ['class' => 'control-label']) }}
            {{ Form::textarea('note', null, ['class' => 'form-control', 'rows' => '3']) }}
            {{ Form::select('importance', \SICV\Presenters\ClientNotePresenter::getSelectOptions(), null, ['class' => 'form-control mb5']) }}
            {{ Form::hidden('client_id', $client->id()) }}

            @if(isset($contract))
                <div class="row">
                    <div class="col-md-8 col-lg-offset-2">
                        <div class="ckbox ckbox-primary">
                            <input type="checkbox" value="{{ $contract->id() }}" id="checkboxPrimary" checked="checked" name="contract_id">
                            <label for="checkboxPrimary">Para este contrato</label>
                        </div>
                    </div>
                </div>
            @endif

            {{ Form::submit('Guardar Nota', ['class' => 'btn btn-primary btn-block disabled submit-ajax']) }}
        </div>
        {{ Form::close() }}
        @endif
    </div>
</div>