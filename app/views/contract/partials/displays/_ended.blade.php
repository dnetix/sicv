<div class="row">
    <div class="col-sm-8">
        <div class="stat">
            <small>Fecha Finalizaci&oacute;n</small>
            <h3>{{ $contract->present()->endDate() }}</h3>
            <div class="text-muted">{{ $contract->present()->endDateDifference() }}</div>
        </div>
        <div class="stat text-center">
            <hr />
            <div class="text-muted">Datos en meses</div>
            {{ $contract->present()->monthStatistics() }}
        </div>

    </div>
    <div class="col-sm-4 amount">
        <div class="stat">
            <small>Valor cancelado</small>
            <h2>{{ $contract->present()->endAmount() }}</h2>
            <div class="text-muted">{{ $contract->present()->payedExtensions() }} en abonos</div>
        </div>
        <div class="stat">
            <small>En saca n&uacute;mero</small>
            <h2><a href="{{ route('sellout.view', $sellout->id()) }}">{{ $sellout->id() }}</a></h2>
        </div>
    </div>
</div>