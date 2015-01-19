@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> Panel Principal</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')
<div class="contentpanel">

    @foreach($contracts as $contract)
        @include('contract.partials._contract_list_item')
    @endforeach

</div>

<!-- contentpanel -->
@endsection