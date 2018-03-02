<!-- snippets -->
<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

if (isset($resources->items['site_snippets'])) { ?>
    <div class="tab-page" id="tabSnippets">
        <h2 class="tab"><i class="fa fa-code"></i> <?= $_lang["manage_snippets"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabSnippets'))</script>

        <div id="snippets-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab"><?= $_lang['snippet_management_msg'] ?></div>
            <p class="viewoptions-message"><?= $_lang['view_options_msg'] ?></p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="<?= $_lang['element_filter_msg'] ?>" id="site_snippets_search" />
                    <div class="input-group-btn">
                        <a class="btn btn-success" href="index.php?a=23"><i class="<?= $_style["actions_new"] ?>"></i> <span><?= $_lang['new_snippet'] ?></span></a>
                        <a class="btn btn-secondary" href="javascript:;" id="snippets-help"><i class="<?= $_style["actions_help"] ?>"></i> <span><?= $_lang['help'] ?></span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_snippets"><i class="fa fa-bars"></i> <span><?= $_lang['btn_view_options'] ?></span></a>
                    </div>
                </div>
            </form>
        </div>

        <?= renderViewSwitchButtons('site_snippets') ?>

        <?= createResourceList('site_snippets', $resources) ?>

        <script>
            initQuicksearch('site_snippets_search', 'site_snippets')
            initViews('sn', 'snippets', 'site_snippets')
        </script>
    </div>
<?php } ?>
