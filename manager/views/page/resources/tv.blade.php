@if (!empty($items))
    <div class="tab-page" id="tabVariables">
        <h2 class="tab"><i class="fa fa-list-alt"></i> {{ ManagerTheme::getLexicon('tmplvars') }}</h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabVariables'))</script>
        <div id="tv-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('tmplvars_management_msg') }}</div>
            <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" id="site_tmplvars_search" />
                    <div class="input-group-btn">
                        <a class="btn btn-success" href="index.php?a=300"><i class="fa fa-plus-circle"></i> <span>{{ ManagerTheme::getLexicon('new_tmplvars') }}</span></a>
                        <a class="btn btn-secondary" href="index.php?a=305"><i class="fa fa-sort"></i> <span>{{ ManagerTheme::getLexicon('template_tv_edit') }}</span></a>
                        <a class="btn btn-secondary" href="javascript:;" id="tv-help"><i class="fa fa-question-circle"></i> <span>{{ ManagerTheme::getLexicon('help') }}</span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_tmplvars"><i class="fa fa-bars"></i> <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span></a>
                    </div>
                </div>
            </form>
        </div>

        @include('manager::partials.switchButtons', ['cssId' => 'site_tmplvars'])

        @include('manager::page.resources._list', ['resourceTable' => 'site_tmplvars', 'items' => $items])

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
            initQuicksearch('site_tmplvars_search', 'site_tmplvars');
            initViews('tv', 'tv', 'site_tmplvars')
        </script>
    </div>
@endif
