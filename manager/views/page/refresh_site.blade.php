@extends('manager::template.page')
@section('content')
    <h1>{{ ManagerTheme::getLexicon('refresh_title') }}</h1>
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="index.php?a=26">
                <i class="{{ $_style['icon_recycle'] }}"></i>{{ ManagerTheme::getLexicon('refresh_site') }}
            </a>
        </div>
    </div>

    <div class="tab-page">
        <div class="container container-body">
            @if($num_rows_pub)
                <p>{!! sprintf(ManagerTheme::getLexicon('refresh_published'), (int)$num_rows_pub) !!}</p>
            @endif
            @if($num_rows_unpub)
                <p>{!! sprintf(ManagerTheme::getLexicon('refresh_unpublished'), (int)$num_rows_unpub) !!}</p>
            @endif
            {!! $cache_log !!}
        </div>
    </div>
@endsection
