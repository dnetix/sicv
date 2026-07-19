@extends('layouts.app')

@section('title', 'Contratos sacados')

@section('content')
    <x-report-header title="Contratos sacados" subtitle="Movidos al almacén, vendidos o chatarrizados en el rango" />

    <x-report-filter :range="$range" :item-types="$itemTypes" />

    @include('reports._closed-contracts-table')
@endsection
