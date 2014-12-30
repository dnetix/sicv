@extends('layouts.base')

@section('content')
    <div class="signinpanel">

        <div class="row">

            <div class="col-md-7">

                <div class="signin-info">
                    <div class="logopanel"><img src="{{ public_assets('images/template/logo_sicv_big.png') }}" alt="" /></div>
                </div>

            </div><!-- col-sm-7 -->

            <div class="col-md-5">

                <form method="post" action="http://themepixels.com/demo/webpage/bracket/index.html">
                    <h4 class="nomargin">Ingreso de usuarios</h4>
                    <p class="mt5 mb20">Logueate para entrar a tu cuenta.</p>

                    <input type="text" class="form-control uname" placeholder="Usuario" />
                    <input type="password" class="form-control pword" placeholder="Contrase&ntilde;a" />
                    <button class="btn btn-success btn-block">Acceder</button>

                </form>
            </div><!-- col-sm-5 -->

        </div><!-- row -->

    </div><!-- signin -->
@endsection