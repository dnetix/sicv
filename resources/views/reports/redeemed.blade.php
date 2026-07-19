@extends('layouts.app')

@section('title', 'Contratos cancelados')

@section('content')
    <x-report-header title="Contratos cancelados" subtitle="Cancelados (rescatados) por el cliente en el rango" />

    <x-report-filter :range="$range" :item-types="$itemTypes" />

    @include('reports._closed-contracts-table')
@endsection
