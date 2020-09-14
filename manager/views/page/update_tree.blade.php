@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script language="javascript">
            var actions = {
                cancel: function() {
                    documentDirty = false;
                    document.location.href = 'index.php?a=2';
                }
            };
        </script>
    @endpush

    <h1>
        <i class="{{ ManagerTheme::getStyle('icon_sitemap') }}"></i>{{ ManagerTheme::getLexicon('update_tree') }}
    </h1>

    {!! ManagerTheme::getStyle('actionbuttons.static.cancel') !!}

    <div class="tab-pane" id="exportPane">
        <script type="text/javascript">
            tpExport = new WebFXTabPane(document.getElementById("exportPane"));
        </script>

        <div class="tab-page" id="tabMain">
            <h2 class="tab">{{ ManagerTheme::getLexicon('update_tree') }}</h2>
            <script type="text/javascript">tpExport.addTabPage(document.getElementById("tabMain"));</script>

            <div class="container container-body">

                <form action="index.php" method="post" name="exportFrm">
                    <div class="form-group">
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                 aria-valuemax="100"></div>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>



    </div>


@endsection
