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

    @include('manager::partials.switchButtons', ['cssId' => 'categories_list'])

    <?php
    $itemsPerCategory = isset($resources->itemsPerCategory) ? $resources->itemsPerCategory : false;
    $types = isset($resources->types) ? $resources->types : false;
    $categories = isset($resources->categories) ? $resources->categories : false;
    ?>

    @if(!$itemsPerCategory)
        {{ ManagerTheme::getLexicon('no_results') }}
    @else
        <?php
        $tpl = array(
            'panelGroup' => file_get_contents(MODX_MANAGER_PATH . 'actions/resources/tpl_panelGroup.tpl'),
            'panelHeading' => file_get_contents(MODX_MANAGER_PATH . 'actions/resources/tpl_panelHeading.tpl'),
            'panelCollapse' => file_get_contents(MODX_MANAGER_PATH . 'actions/resources/tpl_panelCollapse.tpl'),
            'elementsRow' => file_get_contents(MODX_MANAGER_PATH . 'actions/resources/tpl_elementsRow.tpl'),
        );

        // Easily loop through $itemsPerCategory-Array
        $panelGroup = '';
        foreach($categories as $catid => $category) {
            // Prepare collapse content / elements-list
            $panelCollapse = '';
            foreach($itemsPerCategory[$catid] as $el) {
                $resourceTable = $el['type'];
                $ph = prepareElementRowPh($el, $resourceTable, $resources);
                $panelCollapse .= parsePh($tpl['elementsRow'], $ph);
            }

            // Add panel-heading / button
            $panelGroup .= parsePh($tpl['panelHeading'], array(
                'tab' => 'categories_list',
                'category' => $categories[$catid],
                'categoryid' => $catid != '' ? ' <small>(' . $catid . ')</small>' : '',
                'catid' => $catid,
            ));

            // Add panel
            $panelGroup .= parsePh($tpl['panelCollapse'], array(
                'tab' => 'categories_list',
                'catid' => $catid,
                'wrapper' => $panelCollapse,
            ));
        } ?>

        <?=parsePh($tpl['panelGroup'], array(
            'resourceTable' => 'categories_list',
            'wrapper' => $panelGroup
        )); ?>
    @endif


    <script>
        initQuicksearch('categories_list_search', 'categories_list')
        initViews('cat', 'category')
    </script>
</div>
