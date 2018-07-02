@if( ! is_array($items) || empty($items))
    {{ ManagerTheme::getLexicon('no_results') }}
@else
    <?php
    if(!function_exists('parsePh')) {
        /**
         * @param string $tpl
         * @param array $ph
         * @return string
         */
        function parsePh($tpl, $ph)
        {
            $modx = evolutionCMS();
            $_lang = ManagerTheme::getLexicon();

            $tpl = $modx->parseText($tpl, $_lang, '[%', '%]');

            return $modx->parseText($tpl, $ph);
        }
    }

    if(!function_exists('prepareElementRowPh')) {
        /**
         * @param array $row
         * @param string $resourceTable
         * @param EvolutionCMS\Legacy\mgrResources $resources
         * @return array
         */
        function prepareElementRowPh($row, $resourceTable, $resources)
        {
            global $_style;
            $modx = evolutionCMS();
            $_lang = ManagerTheme::getLexicon();

            $types = isset($resources->types[$resourceTable]) ? $resources->types[$resourceTable] : false;

            $_lang["confirm_delete"] = $_lang["delete"];

            switch ($resourceTable) {
                case 'site_templates':
                    $class = $row['selectable'] ? '' : 'disabledPlugin';
                    $lockElementType = 1;
                    $_lang["confirm_delete"] = $_lang["confirm_delete_template"];
                    break;
                case 'site_tmplvars':
                    $class = $row['reltpl'] ? '' : 'disabledPlugin';
                    $lockElementType = 2;
                    $_lang["confirm_delete"] = $_lang["confirm_delete_tmplvars"];
                    break;
                case 'site_htmlsnippets':
                    $class = $row['disabled'] ? 'disabledPlugin' : '';
                    $lockElementType = 3;
                    $_lang["confirm_delete"] = $_lang["confirm_delete_htmlsnippet"];
                    break;
                case 'site_snippets':
                    $class = $row['disabled'] ? 'disabledPlugin' : '';
                    $lockElementType = 4;
                    $_lang["confirm_delete"] = $_lang["confirm_delete_snippet"];
                    break;
                case 'site_plugins':
                    $class = $row['disabled'] ? 'disabledPlugin' : '';
                    $lockElementType = 5;
                    $_lang["confirm_delete"] = $_lang["confirm_delete_plugin"];
                    break;
                case 'site_modules':
                    $class = $row['disabled'] ? '' : 'disabledPlugin';
                    $_lang["confirm_delete"] = $_lang["confirm_delete_module"];
                    break;
                default:
                    return array();
            }

            // Prepare displaying user-locks
            $lockedByUser = '';
            $rowLock = $modx->elementIsLocked($lockElementType, $row['id'], true);
            if ($rowLock && $modx->hasPermission('display_locks')) {
                if ($rowLock['sid'] == $modx->sid) {
                    $title = $modx->parseText($_lang["lock_element_editing"], array(
                        'element_type' => $_lang["lock_element_type_" . $lockElementType],
                        'lasthit_df'   => $rowLock['lasthit_df']
                    ));
                    $lockedByUser = '<span title="' . $title . '" class="editResource" style="cursor:context-menu;">' . $_style['tree_preview_resource'] . '</span>&nbsp;';
                } else {
                    $title = $modx->parseText($_lang["lock_element_locked_by"], array(
                        'element_type' => $_lang["lock_element_type_" . $lockElementType],
                        'username'     => $rowLock['username'],
                        'lasthit_df'   => $rowLock['lasthit_df']
                    ));
                    if ($modx->hasPermission('remove_locks')) {
                        $lockedByUser = '<a href="javascript:;" onclick="unlockElement(' . $lockElementType . ', ' . $row['id'] . ', this);return false;" title="' . $title . '" class="lockedResource"><i class="' . $_style['icons_secured'] . '"></i></a>';
                    } else {
                        $lockedByUser = '<span title="' . $title . '" class="lockedResource" style="cursor:context-menu;"><i class="' . $_style['icons_secured'] . '"></i></span>';
                    }
                }
            }
            if ($lockedByUser) {
                $lockedByUser = '<div class="lockCell">' . $lockedByUser . '</div>';
            }

            // Caption
            if ($resourceTable == 'site_tmplvars') {
                $caption = !empty($row['description']) ? ' ' . $row['caption'] . ' &nbsp; <small>(' . $row['description'] . ')</small>' : ' ' . $row['caption'];
            } else {
                $caption = !empty($row['description']) ? ' ' . $row['description'] : '';
            }

            // Special marks
            $tplInfo = array();
            if ($row['locked']) {
                $tplInfo[] = $_lang['locked'];
            }
            if ($row['id'] == $modx->config['default_template'] && $resourceTable == 'site_templates') {
                $tplInfo[] = $_lang['defaulttemplate_title'];
            }
            $marks = !empty($tplInfo) ? ' <em>(' . implode(', ', $tplInfo) . ')</em>' : '';

            /* row buttons */
            $buttons = '';
            if ($modx->hasPermission($types['actions']['edit'][1])) {
                $buttons .= '<li><a title="' . $_lang["edit_resource"] . '" href="index.php?a=' . $types['actions']['edit'][0] . '&amp;id=' . $row['id'] . '"><i class="fa fa-edit fa-fw"></i></a></li>';
            }
            if ($modx->hasPermission($types['actions']['duplicate'][1])) {
                $buttons .= '<li><a onclick="return confirm(\'' . $_lang["confirm_duplicate_record"] . '\')" title="' . $_lang["resource_duplicate"] . '" href="index.php?a=' . $types['actions']['duplicate'][0] . '&amp;id=' . $row['id'] . '"><i class="fa fa-clone fa-fw"></i></a></li>';
            }
            if ($modx->hasPermission($types['actions']['remove'][1])) {
                $buttons .= '<li><a onclick="return confirm(\'' . $_lang["confirm_delete"] . '\')" title="' . $_lang["delete"] . '" href="index.php?a=' . $types['actions']['remove'][0] . '&amp;id=' . $row['id'] . '"><i class="fa fa-trash fa-fw"></i></a></li>';
            }
            $buttons = $buttons ? '<div class="btnCell"><ul class="elements_buttonbar">' . $buttons . '</ul></div>' : '';

            $catid = $row['catid'] ? $row['catid'] : 0;

            // Placeholders for elements-row
            return array(
                'class'         => $class ? ' class="' . $class . '"' : '',
                'lockedByUser'  => $lockedByUser,
                'name'          => $row['name'],
                'caption'       => $caption,
                'buttons'       => $buttons,
                'marks'         => $marks,
                'id'            => $row['id'],
                'resourceTable' => $resourceTable,
                'actionEdit'    => $types['actions']['edit'][0],
                'catid'         => $catid,
                'textdir'       => ManagerTheme::getTextDir('&rlm;'),
            );
        }
    }
    // Prepare elements- and categories-list
    $elements = array();
    $categories = array();
    foreach($items as $row) {
        $catid = $row['catid'] ? $row['catid'] : 0;
        $categories[$catid] = array('name' => stripslashes($row['category']));
        $elements[$catid][] = prepareElementRowPh($row, $resourceTable, $resources);
    }
    ?>
    @include('manager::partials.panelGroup')
@endif
