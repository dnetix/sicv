<div class="panel panel-warning panel-contract-options">
    <div class="panel-heading">
        <div class="panel-title">Estadisticas de Contratos</div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-3">
                <div class="stat">
                    <small>Valor Total de Contratos</small>
                    <h2>{{ $contractStatistics->present()->totalAmount() }}</h2>
                    <div class="text-muted">En {{ $contractStatistics->present()->numberOfContracts() }} contratos ({{ $contractStatistics->present()->numberOfArticles() }}) articulos</div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat">
                    <small>Total de abonos pagados</small>
                    <h3>{{ $contractStatistics->present()->totalPayedExtensions() }}</h3>
                    <div class="text-muted">({{ $contractStatistics->present()->profitPercentage() }}) de ganancia</div>
                </div>
                <div class="stat">
                    <small>Total de abonos al mes</small>
                    <h3>{{ $contractStatistics->present()->totalPaymentMonth() }}</h3>
                </div>
                <div class="stat">
                    <small>Total de abonos para cancelar todo</small>
                    <h3>{{ $contractStatistics->present()->totalDuedExtensions() }}</h3>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat">
                    <small>Total de peso del oro</small>
                    <h3>{{ $contractStatistics->present()->goldWeight() }} gramos</h3>
                    <div class="text-muted">En {{ $contractStatistics->present()->goldCount() }} articulos</div>
                </div>
            </div>
        </div>

    </div>
</div>