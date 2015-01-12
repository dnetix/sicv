<div class="row">
    <div class="col-sm-8">
        <div class="stat">
            <small>Fecha Vencimiento</small>
            <h3>{{ $contract->present()->dueDate() }}</h3>
            <div class="text-muted">{{ $contract->present()->dueDateDifference() }}</div>
        </div>
        <div class="stat text-center">
            <hr />
            <div class="text-muted">Datos en meses</div>
            {{ $contract->present()->monthStatistics() }}
        </div>

        <div id="contract_cancel" style="display: none;">
            <hr />
            <div class="well">
                {{ Form::open(['route' => 'contract.terminate', 'class' => 'form-horizontal']) }}
                <h3>Cancelar Contrato</h3>
                <div class="row">
                    <div class="form-group">
                        {{ Form::label('amount', 'Valor a cancelar', ['class' => 'control-label col-sm-3']) }}
                        <div class="col-sm-5">
                            {{ Form::text('amount', $contract->present()->amountToTerminate(), ['class' => 'form-control input-lg money']) }}
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                {{ Form::hidden('id', $contract->id()) }}
                                {{ Form::submit('Guardar', ['class' => 'btn btn-primary btn-lg btn-block']) }}
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>

    </div>
    <div class="col-sm-4 amount">
        <div class="stat">
            <small>Valor para Cancelar</small>
            <h2>{{ $contract->present()->amountToTerminate() }}</h2>
            <div class="text-muted">{{ $contract->present()->duedExtensions() }} en abonos</div>
        </div>
        <div class="stat">
            <hr />
            <h5>Operaciones</h5>
            <input type="button" class="btn btn-primary btn-block btn-lg" value="Cancelar Contrato" onclick="$('#contract_cancel').fadeIn()" />
            <input type="button" class="btn btn-danger btn-block" value="Anular Contrato" />
            <input type="button" class="btn btn-default-alt btn-block" value="Mover al almacen" />
            <input type="button" class="btn btn-default-alt btn-block" value="Imprimir Duplicado" />
        </div>
    </div>
</div>