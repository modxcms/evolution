@if (!empty($items))
    <div class="tab-page" id="tabPlugins">
        <h2 class="tab"><i class="fa fa-plug"></i> {{ ManagerTheme::getLexicon('manage_plugins') }}</h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabPlugins'))</script>

        <div id="plugins-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab">{{ ManagerTheme::getLexicon('plugin_management_msg') }}</div>
            <p class="viewoptions-message">{{ ManagerTheme::getLexicon('view_options_msg') }}</p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" id="site_plugins_search" />
                    <div class="input-group-btn">
                        <?php if ($modx->hasPermission('new_plugin')) { ?>
                            <a class="btn btn-success" href="index.php?a=101"><i class="fa fa-plus-circle"></i> <span>{{ ManagerTheme::getLexicon('new_plugin') }}</span></a>
                        <?php } ?>
                        <?php if ($modx->hasPermission('save_plugin')) { ?>
                            <a class="btn btn-secondary" href="index.php?a=100"><i class="fa fa-sort"></i> <span>{{ ManagerTheme::getLexicon('plugin_priority') }}</span></a>
                        <?php } ?>
                        <?php
                        if ($modx->hasPermission('delete_plugin') && $_SESSION['mgrRole'] == 1) {
                            $tbl_site_plugins = $modx->getDatabase()->getFullTableName('site_plugins');
                            if ($modx->getDatabase()->getRecordCount($modx->getDatabase()->query("SELECT id FROM {$tbl_site_plugins} t1 WHERE disabled = 1 AND name IN (SELECT name FROM {$tbl_site_plugins} t2 WHERE t1.name = t2.name AND t1.id != t2.id)"))) { ?>
                                <a onclick="return confirm('{{ ManagerTheme::getLexicon('purge_plugin_confirm') }}')" class="btn btn-danger" href="index.php?a=119">{{ ManagerTheme::getLexicon('purge_plugin') }}</a>
                                <?php
                            }
                        } ?>
                        <a class="btn btn-secondary" href="javascript:;" id="plugins-help"><i class="fa fa-question-circle"></i> <span>{{ ManagerTheme::getLexicon('help') }}</span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_plugins"><i class="fa fa-bars"></i> <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span></a>
                    </div>
                </div>
            </form>
        </div>

        @include('manager::partials.switchButtons', ['cssId' => 'site_plugins'])

        @include('manager::page.resources._list', ['resourceTable' => 'site_plugins', 'items' => $items])

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
            initQuicksearch('site_plugins_search', 'site_plugins');
            initViews('pl', 'plugins', 'site_plugins')
        </script>
    </div>
@endif
