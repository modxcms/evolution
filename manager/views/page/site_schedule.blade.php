@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script src="media/script/tablesort.js"></script>
    @endpush
    <h1>
        <i class="{{ $_style['icon_calendar'] }}"></i>{{ ManagerTheme::getLexicon('site_schedule') }}
    </h1>

    <div class="tab-page">
        <div class="container container-body">
            <div class="form-group">
                <b>{{ ManagerTheme::getLexicon('publish_events') }}</b>
                @if($publishedDocs->count() === 0)
                    <p>{{ ManagerTheme::getLexicon('no_docs_pending_publishing') }}</p>
                @else
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table data" id="table-1">
                                <thead>
                                <tr>
                                    <th class="sortable" style="width: 1%">{{ ManagerTheme::getLexicon('id') }}</th>
                                    <th class="sortable">{{ ManagerTheme::getLexicon('resource') }}</th>
                                    <th class="sortable text-right" style="width: 15%">{{ ManagerTheme::getLexicon('publish_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($publishedDocs as $row)
                                    <?php /** @var EvolutionCMS\Models\SiteContent $row */ ?>
                                    <tr>
                                        <td class="text-right">{{ $row->getKey() }}</td>
                                        <td><a href="{{ $row->makeUrl('actions.info') }}">{{ $row->nodeName }}</a></td>
                                        <td class="text-nowrap text-right">{{ $row->pub_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <b>{{ ManagerTheme::getLexicon('unpublish_events') }}</b>
                @if($unpublishedDocs->count() === 0)
                    <p>{{ ManagerTheme::getLexicon('no_docs_pending_unpublishing') }}</p>
                @else
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table data" id="table-2">
                                <thead>
                                <tr>
                                    <th class="sortable" style="width: 1%">{{ ManagerTheme::getLexicon('id') }}</th>
                                    <th class="sortable">{{ ManagerTheme::getLexicon('resource') }}</th>
                                    <th class="sortable text-right" style="width: 15%">{{ ManagerTheme::getLexicon('unpublish_date') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($unpublishedDocs as $row)
                                    <?php /** @var EvolutionCMS\Models\SiteContent $row */ ?>
                                    <tr>
                                        <td class="text-right">{{ $row->getKey() }}</td>
                                        <td><a href="{{ $row->makeUrl('actions.info') }}">{{ $row->nodeName }}</a></td>
                                        <td class="text-nowrap text-right">{{ $row->unPub_at }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <b>{{ ManagerTheme::getLexicon('all_events') }}</b>
                @if($allDocs->count() === 0)
                    <p>{{ ManagerTheme::getLexicon('no_docs_pending_pubunpub') }}</p>
                @else
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table data" id="table-3">
                                <thead>
                                <tr>
                                    <th class="sortable" style="width: 1%"><b>{{ ManagerTheme::getLexicon('id') }}</b></th>
                                    <th class="sortable"><b>{{ ManagerTheme::getLexicon('resource') }}</b></th>
                                    <th class="sortable text-right" style="width: 15%"><b>{{ ManagerTheme::getLexicon('publish_date') }}</b></th>
                                    <th class="sortable text-right" style="width: 15%"><b>{{ ManagerTheme::getLexicon('unpublish_date') }}</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($allDocs as $row)
                                    <?php /** @var EvolutionCMS\Models\SiteContent $row */ ?>
                                    <tr>
                                        <td class="text-right">{{ $row->getKey() }}</td>
                                        <td><a href="{{ $row->makeUrl('actions.info') }}">{{ $row->nodeName }}</a></td>
                                        <td class="text-nowrap text-right">{{ $row->pub_at ?? '-' }}</td>
                                        <td class="text-nowrap text-right">{{ $row->unPub_at ?? '-'}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
