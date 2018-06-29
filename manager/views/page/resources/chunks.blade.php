@if(isset($resources->items['site_htmlsnippets']))
<div class="tab-page" id="tabChunks">
    <h2 class="tab"><i class="fa fa-th-large"></i> <?= $_lang["manage_htmlsnippets"] ?></h2>
    <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabChunks'))</script>

    <div id="chunks-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab"><?= $_lang['htmlsnippet_management_msg'] ?></div>
        <p class="viewoptions-message"><?= $_lang['view_options_msg'] ?></p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input class="form-control filterElements-form" type="text" size="30" placeholder="<?= $_lang['element_filter_msg'] ?>" id="site_htmlsnippets_search" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="index.php?a=77"><i class="<?= $_style["actions_new"] ?>"></i> <span><?= $_lang['new_htmlsnippet'] ?></span></a>
                    <a class="btn btn-secondary" href="javascript:;" id="chunks-help"><i class="<?= $_style["actions_help"] ?>"></i> <span><?= $_lang['help'] ?></span></a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_htmlsnippets"><i class="fa fa-bars"></i> <span><?= $_lang['btn_view_options'] ?></span></a>
                </div>
            </div>
        </form>
    </div>

    @include('manager::partials.switchButtons', ['cssId' => 'site_htmlsnippets'])

    @include('manager::page.resources._list', ['resourceTable' => 'site_htmlsnippets', 'items' => $resources->items['site_htmlsnippets']])

    <script>
        initQuicksearch('site_htmlsnippets_search', 'site_htmlsnippets')
        initViews('ch', 'chunks', 'site_htmlsnippets')
    </script>
</div>
@endif
