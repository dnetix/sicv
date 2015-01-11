@extends('layouts.default')

@section('pageheader')
    <div class="pageheader">
        <h2><i class="fa fa-user"></i> Detalles de cliente</h2>
        <div class="breadcrumb-wrapper">
            <span class="label">{{ Date::toHumanDate() }}</span>
        </div>
    </div>
@endsection

@section('content')

    <?php
            $client = \SICV\Clients\Client::find(1);
    ?>

<div class="contentpanel">

    <div class="row">
        @include('client.partials._client_profile')
    </div>

    <div class="row">
        <div class="col-md-9">
            @include('client.partials._client_profile')
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            @include('client.partials._client_profile')
        </div>
    </div>

</div>
<!-- contentpanel -->
@endsection