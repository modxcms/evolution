@if (!empty($items))
    <div class="tab-page" id="tabModules">
        <h2 class="tab"><i class="fa fa-cubes"></i> {{ ManagerTheme::getLexicon('modules') }}</h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabModules'))</script>

        <div id="chunks-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('module_management_msg') }}</div>
            <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" id="site_modules_search" />
                    <div class="input-group-btn">
                        <a class="btn btn-success" href="index.php?a=107"><i class="fa fa-plus-circle"></i> <span>{{ ManagerTheme::getLexicon('new_module') }}</span></a>
                        <a class="btn btn-secondary" href="javascript:;" id="chunks-help"><i class="fa fa-question-circle"></i> <span>{{ ManagerTheme::getLexicon('help') }}</span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_modules"><i class="fa fa-bars"></i> <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span></a>
                    </div>
                </div>
            </form>
        </div>

        @include('manager::partials.switchButtons', ['cssId' => 'site_modules'])

        <hr />

        @foreach($categories as $cat)
            <?php /** @var EvolutionCMS\Models\Category $cat */?>
            {{ $cat->rank }}
            @foreach($cat->modules as $item)
                <?php /** @var EvolutionCMS\Models\SiteModule $item */?>
                {{ $item->name }} <a href="{{ $item->makeUrl('actions.edit') }}">Edit</a><br />
            @endforeach
        @endforeach

        <hr />

        @foreach($outCategory as $item)
            <?php /** @var EvolutionCMS\Models\SiteModule $item */?>
            {{ $item->name }} <a href="{{ $item->makeUrl('actions.edit') }}">Edit</a><br />
        @endforeach

        <script>
            initQuicksearch('site_htmlsnippets_search', 'site_htmlsnippets');
            initViews('ch', 'chunks', 'site_htmlsnippets')
        </script>
    </div>
@endif
