@if (!empty($items))
<div class="tab-page" id="tabChunks">
    <h2 class="tab"><i class="fa fa-th-large"></i> {{ ManagerTheme::getLexicon('manage_htmlsnippets') }}</h2>
    <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabChunks'))</script>

    <div id="chunks-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('htmlsnippet_management_msg') }}</div>
        <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input class="form-control filterElements-form" type="text" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" id="site_htmlsnippets_search" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="index.php?a=77"><i class="fa fa-plus-circle"></i> <span>{{ ManagerTheme::getLexicon('new_htmlsnippet') }}</span></a>
                    <a class="btn btn-secondary" href="javascript:;" id="chunks-help"><i class="fa fa-question-circle"></i> <span>{{ ManagerTheme::getLexicon('help') }}</span></a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_htmlsnippets"><i class="fa fa-bars"></i> <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span></a>
                </div>
            </div>
        </form>
    </div>

    @include('manager::partials.switchButtons', ['cssId' => 'site_htmlsnippets'])

    @include('manager::page.resources.tab._list', ['resourceTable' => 'site_htmlsnippets', 'items' => $items])

    <hr />

    @foreach($categories as $cat)
        {{ $item->rank }}
        @foreach($cat as $item)
            {{ $item->name }} <br />
        @endforeach
    @endforeach

    <hr />

    @foreach($outCategory as $item)
        {{ $item->name }} <br />
    @endforeach

    <script>
        initQuicksearch('site_htmlsnippets_search', 'site_htmlsnippets');
        initViews('ch', 'chunks', 'site_htmlsnippets')
    </script>
</div>
@endif
