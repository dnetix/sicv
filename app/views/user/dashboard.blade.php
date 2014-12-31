@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Panel Principal</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ date_print_format(date('Y-m-d')) }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    <div class="row">
        <div class="col-md-3 col-sm-4 col-xs-12">
            <div class="panel panel-success panel-stat">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-4">
                            <img src="{{ public_assets('images/template/is-user.png') }}" alt="" />
                        </div>
                        <div class="col-xs-8">
                            <small class="stat-label">B&uacute;squeda</small>
                            <h1>Clientes</h1>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">
                            {{ Form::open() }}
                                <div class="form-group">
                                    {{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Buscar cliente']) }}
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-dark panel-stat">
                <a href="#">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-4">
                                <img src="{{ public_assets('images/template/is-document.png') }}" alt="" />
                            </div>
                            <div class="col-xs-8">
                                <small class="stat-label">Nuevo</small>
                                <h1>Contrato</h1>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="panel panel-warning panel-stat">
                <a href="#">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-4">
                                <img src="{{ public_assets('images/template/is-money.png') }}" alt="" />
                            </div>
                            <div class="col-xs-8">
                                <small class="stat-label">Precio</small>
                                <h1>Oro</h1>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="col-md-9 col-sm-8 col-xs-12">

            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="subtitle mb5">Contratos Hoy</h5>
                        </div>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Contrato</th>
                                        <th>Cliente / Articulo</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><a href="#">23345</a></td>
                                        <td>
                                            <p><a href="#">Diego Arturo Calle Mora</a></p>
                                            <p>Computador HP Folio 13 Intel Core i5 4GB RAM</p>
                                        </td>
                                        <td>$ 1'800.000</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">23345</a></td>
                                        <td>
                                            <p><a href="#">Diego Arturo Calle Mora</a></p>
                                            <p>Computador HP Folio 13 Intel Core i5 4GB RAM</p>
                                        </td>
                                        <td>$ 1'800.000</td>
                                    </tr>
                                    <tr>
                                        <td><a href="#">23345</a></td>
                                        <td>
                                            <p><a href="#">Diego Arturo Calle Mora</a></p>
                                            <p>Computador HP Folio 13 Intel Core i5 4GB RAM</p>
                                        </td>
                                        <td>$ 1'800.000</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th>$ 5'400.000</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- contentpanel -->
@endsection