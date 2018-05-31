<!-- Templates -->
<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
}

if (isset($resources->items['site_templates'])) { ?>
    <div class="tab-page" id="tabTemplates">
        <h2 class="tab"><i class="fa fa-newspaper-o"></i> <?= $_lang["manage_templates"] ?></h2>
        <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabTemplates'))</script>
        <div id="template-info" class="msg-container" style="display:none">
            <div class="element-edit-message-tab"><?= $_lang['template_management_msg'] ?></div>
            <p class="viewoptions-message"><?= $_lang['view_options_msg'] ?></p>
        </div>

        <div id="_actions">
            <form class="btn-group form-group form-inline">
                <div class="input-group input-group-sm">
                    <input class="form-control filterElements-form" type="text" size="30" placeholder="<?= $_lang['element_filter_msg'] ?>" id="site_templates_search" />
                    <div class="input-group-btn">
                        <a class="btn btn-success" href="index.php?a=19"><i class="<?= $_style["actions_new"] ?>"></i> <span><?= $_lang['new_template'] ?></span></a>
                        <a class="btn btn-secondary" href="javascript:;" id="template-help"><i class="<?= $_style["actions_help"] ?>"></i> <span><?= $_lang['help'] ?></span></a>
                        <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_templates"><i class="fa fa-bars"></i> <span><?= $_lang['btn_view_options'] ?></span></a>
                    </div>
                </div>
            </form>
        </div>

        <?= renderViewSwitchButtons('site_templates') ?>

        <?= createResourceList('site_templates', $resources) ?>

        <script>
            initQuicksearch('site_templates_search', 'site_templates')
            initViews('tmp', 'template', 'site_templates')
        </script>
    </div>
<?php } ?>
