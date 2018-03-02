<?php
if( ! defined('IN_MANAGER_MODE') || IN_MANAGER_MODE !== true) {
    die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the EVO Content Manager instead of accessing this file directly.");
} ?>

<!-- category view -->
<div class="tab-page" id="tabCategory">
    <h2 class="tab"><?= $_lang["element_categories"] ?></h2>
    <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabCategory'))</script>

    <div id="category-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab"><?= $_lang['category_msg'] ?></div>
        <p class="viewoptions-message"><?= $_lang['view_options_msg'] ?></p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input class="form-control filterElements-form" type="text" size="30" placeholder="<?= $_lang['element_filter_msg'] ?>" id="categories_list_search" />
                <div class="input-group-btn">
                    <a class="btn btn-secondary" href="index.php?a=120"><i class="<?= $_style["actions_categories"] ?>"></i> <span><?= $_lang['manage_categories'] ?></span></a>
                    <a class="btn btn-secondary" href="javascript:;" id="category-help"><i class="<?= $_style["actions_help"] ?>"></i> <span><?= $_lang['help'] ?></span></a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_categories_list"><i class="fa fa-bars"></i> <span><?= $_lang['btn_view_options'] ?></span></a>
                </div>
            </div>
        </form>
    </div>

    <?= renderViewSwitchButtons('categories_list') ?>

    <?= createCombinedView($resources) ?>

    <script>
        initQuicksearch('categories_list_search', 'categories_list')
        initViews('cat', 'category')
    </script>
</div>
