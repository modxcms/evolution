@extends('manager::template.page')
@section('content')
    <h1>{{ \Lang::get('global.web_user_title') }}</h1>

    <div id="actions">
        <div class="btn-group">
            <a href="{{ $url }}"><i
                        class="{{ $_style["icon_save"] }}"></i> {{ \Lang::get('global.edit') }}
            </a>
        </div>
    </div>

    <div class="sectionBody">
        <div class="tab-page">
            <div class="container container-body" id="disp">
                <p>
                    {!! \Lang::get('global.password_msg', ['username' => $username, 'password'=>$password]) !!}
                </p>
            </div>
        </div>
    </div>


@endsection
