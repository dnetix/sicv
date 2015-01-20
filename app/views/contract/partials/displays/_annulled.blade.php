<div class="row">
    <div class="col-sm-8">
        <div class="stat">
            <small>Fecha Anulaci&oacute;n</small>
            <h3>{{ $annul->present()->createdAt() }}</h3>
            <div class="text-muted">{{ $annul->present()->createdAtDifference() }}</div>
        </div>
        <div class="stat">
            <small>Anulado Por</small>
            <h3>{{ $annul->present()->userName() }}</h3>
        </div>
    </div>
    <div class="col-sm-4 amount">
        <div class="stat">
            <small>Valor Original</small>
            <h2>{{ $annul->present()->originalAmount() }}</h2>
        </div>
    </div>
</div>
<div class="row">
    <div class="well">
        <h4>Motivo anulaci&oacute;n</h4>
        <p>{{ $annul->present()->note() }}</p>
    </div>
</div>