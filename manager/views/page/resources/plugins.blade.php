@extends('manager::template.page')
@section('content')
    <script>var trans = '{{ json_encode($unlockTranslations) }}';</script>
    <script>var mraTrans = '{{ json_encode($mraTranslations) }}';</script>

    <script type="text/javascript" src="media/script/jquery.quicksearch.js"></script>
    <script type="text/javascript" src="media/script/jquery.nucontextmenu.js"></script>
    <script type="text/javascript" src="media/script/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="actions/resources/functions.js"></script>

    <h1>
        <i class="fa fa-th"></i>{{ ManagerTheme::getLexicon('element_management') }}
    </h1>

    <div class="sectionBody">
        <div class="tab-pane" id="resourcesPane">
            <script type="text/javascript">
                tpResources = new WebFXTabPane(document.getElementById("resourcesPane"), true);
            </script>
            @include('manager::page.resources.tab.plugins')
            @if(is_numeric(get_by_key($_GET, 'tab')))
                <script type="text/javascript"> tpResources.setSelectedIndex({{ $_GET['tab'] }});</script>
            @endif
        </div>
    </div>
@endsection
