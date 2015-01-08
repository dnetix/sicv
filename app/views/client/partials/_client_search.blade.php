<div class="panel panel-success">
    <div class="panel-heading">
        <div class="panel-btns">
            <a href="javascript:void(0)" onclick="jQuery(this).closest('.panel').fadeOut(200)" class="panel-close">×</a>
        </div>
        <div class="panel-title">Resultados de Busqueda {{$link}}</div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-email">
                <tbody>
                @forelse($clients as $client)
                    @include('client.partials._client_search_item')
                @empty
                    <h4>No hay clientes que coincidan con la busqueda</h4>
                    <p>{{ link_to_route('client.new', 'Crea un nuevo cliente') }}</p>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>