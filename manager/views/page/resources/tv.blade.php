@if (isset($resources->items['site_tmplvars']))
    <div class="tab-page" id="tabVariables">
        <h2 class="tab"><i class="fa fa-list-alt"></i> <?= $_lang["tmplvars"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabVariables'))</script>
        <!--//
            Modified By Raymond for Template Variables
            Added by Apodigm 09-06-2004- DocVars - web@apodigm.com
        -->
        <div id="tv-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab"><?= $_lang['tmplvars_management_msg'] ?></div>
            <p class="viewoptions-message"><?= $_lang['view_options_msg'] ?></p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="<?= $_lang['element_filter_msg'] ?>" id="site_tmplvars_search" />
                    <div class="input-group-btn">
                        <a class="btn btn-success" href="index.php?a=300"><i class="<?= $_style["actions_new"] ?>"></i> <span><?= $_lang['new_tmplvars'] ?></span></a>
                        <a class="btn btn-secondary" href="index.php?a=305"><i class="<?= $_style["actions_sort"] ?>"></i> <span><?= $_lang['template_tv_edit'] ?></span></a>
                        <a class="btn btn-secondary" href="javascript:;" id="tv-help"><i class="<?= $_style["actions_help"] ?>"></i> <span><?= $_lang['help'] ?></span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_tmplvars"><i class="fa fa-bars"></i> <span><?= $_lang['btn_view_options'] ?></span></a>
                    </div>
                </div>
            </form>
        </div>

        @include('manager::partials.switchButtons', ['cssId' => 'site_tmplvars'])

        @include('manager::page.resources._list', ['resourceTable' => 'site_tmplvars', 'items' => $resources->items['site_tmplvars']])

        <script>
            initQuicksearch('site_tmplvars_search', 'site_tmplvars')
            initViews('tv', 'tv', 'site_tmplvars')
        </script>
    </div>
@endif
