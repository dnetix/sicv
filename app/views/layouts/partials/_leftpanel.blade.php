<div class="leftpanel">

    <div class="logopanel">
        <h1>SICV</h1>
    </div>

    <div class="leftpanelinner">

        <h5 class="sidebartitle">Navegaci&oacute;n</h5>
        <ul class="nav nav-pills nav-stacked nav-bracket">
            <li class="active"><a href="{{ route('user.dashboard') }}"><i class="fa fa-home"></i> <span>Panel Principal</span></a></li>
            <li class="nav-parent"><a href="{{ route('contract.new') }}"><i class="fa fa-edit"></i> <span>Contrato</span></a>
                <ul class="children">
                    <li><a href="{{ route('contract.new') }}"><i class="fa fa-caret-right"></i> Nuevo Contrato</a></li>
                </ul>
            </li>
            <li class="nav-parent"><a href="#"><i class="fa fa-user"></i> <span>Cliente</span></a>
                <ul class="children">
                    <li><a href="{{ route('client.new') }}"><i class="fa fa-caret-right"></i> Nuevo Cliente</a></li>
                </ul>
            </li>
            <li class="nav-parent"><a href="#"><i class="fa fa-book"></i> <span>Informes</span></a>
                <ul class="children">
                    <li><a href="{{ route('report.expiredcontracts') }}"><i class="fa fa-caret-right"></i> Contratos Vencidos</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>