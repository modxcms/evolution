@extends('manager::template.page')
@section('content')
    <h1>
        <i class="{{ $_style['icon_info_circle'] }}"></i>{{ ManagerTheme::getLexicon('view_sysinfo') }}
    </h1>

    <!-- server -->
    <div class="tab-page">
        <div class="container container-body">
            <p><b>Server</b></p>
            <div class="row">
                <div class="table-responsive">
                    <table class="table data table-sm nowrap">
                        <tbody>
                        @foreach ($serverArr as $key => $value)
                            <tr>
                                <td width="1%">{{ empty($value['is_lexicon']) ? $key : ManagerTheme::getLexicon($key) }}</td>
                                <td>&nbsp;</td>
                                <td>
                                    @if (isset($value['render']))
                                        @include($value['render'], ['data' => $value['data']])
                                    @else
                                        <b>{{ $value['data'] }}</b>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
