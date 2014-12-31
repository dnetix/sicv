@extends('layouts.base')

@section('content')
    <div class="signinpanel">

        <div class="row">

            <div class="col-md-6">

                <div class="signin-info text-center">
                    <div class="logopanel mb20">
                        <img src="{{ public_assets('images/template/logo_sicv_big.png') }}" alt="" />
                    </div>
                    @include('layouts.partials._flash_notification')
                </div>

            </div>

            <div class="col-md-6">

                {{ Form::open() }}
                    <h4 class="nomargin">Ingreso de usuarios</h4>
                    <p class="mt5 mb20">Logueate para entrar a tu cuenta.</p>

                    <div class="form-group">
                        {{ Form::text('username', null, ['class' => 'form-control uname', 'placeholder' => 'Usuario', 'required' => 'required']) }}
                    </div>

                    <div class="form-group">
                        {{ Form::password('password', ['class' => 'form-control pword', 'placeholder' => 'Contrase&ntilde;a', 'required' => 'required']) }}
                    </div>

                    <div class="form-group text-center">
                        {{ Form::submit('Acceder', ['class' => 'btn btn-success btn-block']) }}
                    </div>
                {{ Form::close() }}

            </div>

        </div>

    </div>
@endsection