<div class="tab-page" id="{{ $tabPageName }}">
    <h2 class="tab">
        <a href="?a=76&tab={{ $tabId }}"><i class="fa fa-code"></i> {{ ManagerTheme::getLexicon('manage_snippets') }}</a>
    </h2>
    <script>tpResources.addTabPage(document.getElementById('tabSnippets'));</script>

    <div id="snippets-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('snippet_management_msg') }}</div>
        <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input class="form-control filterElements-form" type="text" id="{{ $tabName }}_search" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="{{ (new EvolutionCMS\Models\SiteSnippet)->makeUrl('actions.new') }}">
                        <i class="fa fa-plus-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('new_snippet') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="javascript:;" id="snippets-help">
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
            @if(isset($outCategory) && $outCategory->count() > 0)
                @component('manager::partials.panelCollapse', ['name' => $tabName, 'id' => 0, 'title' => ManagerTheme::getLexicon('no_category')])
                    <ul class="elements">
                        @foreach($outCategory as $item)
                            @include('manager::page.resources.elements.snippet', compact('item', 'tabName'))
                        @endforeach
                    </ul>
                @endcomponent
            @endif

            @if(isset($categories))
                @foreach($categories as $cat)
                    @component('manager::partials.panelCollapse', ['name' => $tabName, 'id' => $cat->id, 'title' => $cat->name])
                        <ul class="elements">
                            @foreach($cat->snippets as $item)
                                @include('manager::page.resources.elements.snippet', compact('item', 'tabName'))
                            @endforeach
                        </ul>
                    @endcomponent
                @endforeach
            @endif
        </div>
    </div>
    <div class="clearfix"></div>
</div>

@push('scripts.bot')
    <script>
      initQuicksearch('{{ $tabName }}_search', '{{ $tabName }}');
      initViews('sn', 'snippets', '{{ $tabName }}');
    </script>
@endpush
