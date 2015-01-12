<div class="panel panel-dark">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="javascript:void(0)" onclick="getContractsOfDay('{{ Date::create((isset($day) ? $day : null))->changeDays(+1)->toSQLDate() }}')" class="fa fa-chevron-circle-right">&nbsp;</a>
            <a href="javascript:void(0)" onclick="getContractsOfDay('{{ Date::create((isset($day) ? $day : null))->changeDays(-1)->toSQLDate() }}')" class="fa fa-chevron-circle-left">&nbsp;</a>
        </div>
        <div class="panel-title">Contratos del d&iacute;a{{ (isset($day) && !Date::getDifference($day)->isToday()) ? ' ['.Date::toHumanDate($day).']' : '' }}</div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped">
                @if($contracts->count() > 0)
                <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Cliente / Articulo</th>
                    <th>Valor</th>
                    <th>Estado</th>
                </tr>
                </thead>
                @endif
                <tbody>
                @forelse($contracts as $contract)
                    @include('contract.partials._contract_list_item')
                @empty
                    <tr><th colspan="3">No hay Contratos del dia</th> </tr>
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