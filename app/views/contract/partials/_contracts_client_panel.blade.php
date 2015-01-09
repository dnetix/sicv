<div class="panel panel-dark">
    <div class="panel-heading">
        <div class="panel-btns">

        </div>
        <div class="panel-title">Contratos del cliente</div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                @if($contracts->count() > 0)
                <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Articulo</th>
                    <th>Valor</th>
                </tr>
                </thead>
                @endif
                <tbody>
                @forelse($contracts as $contract)
                    @include('contract.partials._contract_list_item', ['isClient' => true])
                @empty
                    <tr><th colspan="3">El cliente no tiene contratos</th> </tr>
                @endforelse
                </tbody>
                @if($contracts->count() > 0)
                    <tfoot>
                        <tr>
                            <th colspan="2">Total</th>
                            <th colspan="2">{{ '$ '.number_format($contracts->sum('amount')) }}</th>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>