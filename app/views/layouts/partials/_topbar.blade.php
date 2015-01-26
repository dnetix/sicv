<div class="headerbar">

    <a class="menutoggle"><i class="fa fa-bars"></i></a>

    {{ Form::open(['route' => 'user.search', 'class' => 'searchform']) }}
        {{ Form::text('searchTerms', null, ['autocomplete' => 'off', 'class' => 'form-control', 'placeholder' => 'B&uacute;squeda r&aacute;pida', 'id' => 'quicksearch']) }}
    {{ Form::close() }}


    @if($currentUser)
    <div class="header-right">
        <ul class="headermenu">
            <li>
                <div class="btn-group">
                    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ public_assets('images/profile/user.png') }}" alt=""/>
                        <span class="hidden-xs">{{ $currentUser->name }}</span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-usermenu pull-right">
                        <li><a href="profile.html"><i class="glyphicon glyphicon-user"></i> My Profile</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-cog"></i> Account Settings</a></li>
                        <li><a href="#"><i class="glyphicon glyphicon-question-sign"></i> Help</a></li>
                        <li><a href="{{ route('user.logout') }}"><i class="glyphicon glyphicon-log-out"></i> Cerrar Sesi&oacute;n</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
    @endif

</div>