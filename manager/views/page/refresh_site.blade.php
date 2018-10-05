@extends('manager::template.page')
@section('content')
    <h1>{{ $_lang['refresh_title'] }}</h1>
    <div id="actions">
        <div class="btn-group">
            <a id="Button1" class="btn btn-success" href="index.php?a=26">
                <i class="fa fa-recycle"></i> <span>{{ $_lang['refresh_site'] }}</span>
            </a>
        </div>
    </div>

    <div class="tab-page">
        <div class="container container-body">
            @if($num_rows_pub)
                <p>{!! sprintf($_lang["refresh_published"], (int)$num_rows_pub) !!}</p>
            @endif
            @if($num_rows_unpub)
                <p>{!! sprintf($_lang["refresh_unpublished"], (int)$num_rows_unpub) !!}</p>
            @endif
            {!! $cache_log !!}
        </div>
    </div>
@endsection
