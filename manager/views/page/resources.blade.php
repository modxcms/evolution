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

            @foreach($tabs as $tab)
                {!! $tab !!}
            @endforeach


            <?php $tab = (is_numeric(get_by_key($_GET, 'tab')) && count($tabs) >= $_GET['tab']) ? $_GET['tab'] : 0; ?>
            <script type="text/javascript"> tpResources.setSelectedIndex({{ $tab }});</script>
        </div>
    </div>
@endsection
