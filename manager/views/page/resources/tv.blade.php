<div class="tab-page" id="tabVariables">
    <h2 class="tab">
        <i class="fa fa-list-alt"></i> {{ ManagerTheme::getLexicon('tmplvars') }}
    </h2>
    <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabVariables'));</script>
    <div id="tv-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{!! ManagerTheme::getLexicon('tmplvars_management_msg') !!}</div>
        <p class="viewoptions-message">{!! ManagerTheme::getLexicon('view_options_msg') !!}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control filterElements-form" id="site_tmplvars_search" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="{{ (new EvolutionCMS\Models\SiteTmplvar)->makeUrl('actions.new') }}">
                        <i class="fa fa-plus-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('new_tmplvars') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="{{ (new EvolutionCMS\Models\SiteTmplvar)->makeUrl('actions.sort') }}">
                        <i class="fa fa-sort"></i>
                        <span>{{ ManagerTheme::getLexicon('template_tv_edit') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="javascript:;" id="tv-help">
                        <i class="fa fa-question-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('help') }}</span>
                    </a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_tmplvars">
                        <i class="fa fa-bars"></i>
                        <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    @include('manager::page.resources.helper.switchButtons', [
        'tabName' => $tabName
    ])

    <div class="clearfix"></div>
    <div class="panel-group no-transition">
        <div id="{{ $tabName }}" class="resourceTable panel panel-default">
            @if(! empty($outCategory))
                @component('manager::page.resources.helper.panelCollapse', ['name' => $tabName, 'id' => 0, 'title' => ManagerTheme::getLexicon('no_category')])
                    <ul class="elements">
                        @foreach($outCategory as $item)
                            @include('manager::page.resources.elements.tv', ['item' => $item])
                        @endforeach
                    </ul>
                @endcomponent
            @endif

            @foreach($categories as $cat)
                @component('manager::page.resources.helper.panelCollapse', ['name' => $tabName, 'id' => $cat->id, 'title' => $cat->category])
                    <ul class="elements">
                        @foreach($cat->tvs as $item)
                            @include('manager::page.resources.elements.tv', ['item' => $item])
                        @endforeach
                    </ul>
                @endcomponent
            @endforeach
        </div>
    </div>
    <div class="clearfix"></div>

    @push('scripts.bot')
        <script>
          initQuicksearch('{{ $tabName }}_search', '{{ $tabName }}');
          initViews('tv', 'tv', '{{ $tabName }}');
        </script>
    @endpush
</div>
