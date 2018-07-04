@extends('manager::template.page')
@section('content')
    @push('scripts.top')
    <script>
      var trans = {!! json_encode($unlockTranslations, JSON_UNESCAPED_UNICODE) !!},
          mraTrans = {!! json_encode($mraTranslations, JSON_UNESCAPED_UNICODE) !!};
    </script>
    <script src="media/script/jquery.quicksearch.js"></script>
    <script src="media/script/jquery.nucontextmenu.js"></script>
    <script src="media/script/bootstrap/js/bootstrap.min.js"></script>
    <script src="actions/resources/functions.js"></script>
    @endpush
    <h1>
        <i class="fa fa-th"></i>{{ ManagerTheme::getLexicon('element_management') }}
    </h1>

    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script type="text/javascript">
              tpResources = new WebFXTabPane(document.getElementById('resourcesPane'), true);
            </script>

            @foreach($tabs as $tab)
                {!! $tab !!}
            @endforeach

            @if($activeTab != '')
                <script> tpResources.setSelectedIndex({{ $activeTab }});</script>
            @endif
        </div>
    </div>
@endsection
