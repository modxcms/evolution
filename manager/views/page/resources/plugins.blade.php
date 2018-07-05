<div class="tab-page" id="tabPlugins">
    <h2 class="tab">
        <i class="fa fa-plug"></i> {{ ManagerTheme::getLexicon('manage_plugins') }}
    </h2>
    <script>tpResources.addTabPage(document.getElementById('tabPlugins'));</script>

    <div id="plugins-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('plugin_management_msg') }}</div>
        <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input class="form-control filterElements-form" type="text" id="{{ $tabName }}_search" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="{{ (new EvolutionCMS\Models\SitePlugin)->makeUrl('actions.new') }}">
                        <i class="fa fa-plus-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('new_plugin') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="{{ (new EvolutionCMS\Models\SitePlugin)->makeUrl('actions.sort') }}">
                        <i class="fa fa-sort"></i>
                        <span>{{ ManagerTheme::getLexicon('plugin_priority') }}</span>
                    </a>
                    @if($checkOldPlugins)
                        <a onclick="return confirm('{{ ManagerTheme::getLexicon('purge_plugin_confirm') }}')" class="btn btn-danger" href="{{ (new EvolutionCMS\Models\SitePlugin)->makeUrl('actions.purge') }}">
                            {{ ManagerTheme::getLexicon('purge_plugin') }}
                        </a>
                    @endif
                    <a class="btn btn-secondary" href="javascript:;" id="plugins-help">
                        <i class="fa fa-question-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('help') }}</span>
                    </a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_{{ $tabName }}">
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
            @if($outCategory->count() > 0)
                @component('manager::partials.panelCollapse', ['name' => $tabName, 'id' => 0, 'title' => ManagerTheme::getLexicon('no_category')])
                    <ul class="elements">
                        @foreach($outCategory as $item)
                            @include('manager::page.resources.elements.plugin', ['item' => $item])
                        @endforeach
                    </ul>
                @endcomponent
            @endif

            @foreach($categories as $cat)
                @component('manager::partials.panelCollapse', ['name' => $tabName, 'id' => $cat->id, 'title' => $cat->name])
                    <ul class="elements">
                        @foreach($cat->plugins as $item)
                            @include('manager::page.resources.elements.plugin', ['item' => $item])
                        @endforeach
                    </ul>
                @endcomponent
            @endforeach
        </div>
    </div>
    <div class="clearfix"></div>
</div>

@push('scripts.bot')
    <script>
      initQuicksearch('{{ $tabName }}_search', '{{ $tabName }}');
      initViews('pl', 'plugins', '{{ $tabName }}');
    </script>
@endpush
