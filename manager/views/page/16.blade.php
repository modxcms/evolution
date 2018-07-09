@extends('manager::template.page')
@section('content')
    <?php
    switch ($modx->getManagerApi()->action) {
        case 16:
            if (!$modx->hasPermission('edit_template')) {
                $modx->webAlertAndQuit(ManagerTheme::getLexicon("error_no_privileges"));
            }
            break;
        case 19:
            if (!$modx->hasPermission('new_template')) {
                $modx->webAlertAndQuit(ManagerTheme::getLexicon("error_no_privileges"));
            }
            break;
        default:
            $modx->webAlertAndQuit(ManagerTheme::getLexicon("error_no_privileges"));
    }

    $id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

    $tbl_site_templates = $modx->getDatabase()
        ->getFullTableName('site_templates');

    // check to see the snippet editor isn't locked
    if ($lockedEl = $modx->elementIsLocked(1, $id)) {
        $modx->webAlertAndQuit(sprintf(ManagerTheme::getLexicon('lock_msg'), $lockedEl['username'], ManagerTheme::getLexicon('template')));
    }
    // end check for lock

    // Lock snippet for other users to edit
    $modx->lockElement(1, $id);

    $content = array();
    if (!empty($id)) {
        $rs = $modx->getDatabase()
            ->select('*', $tbl_site_templates, "id='{$id}'");
        $content = $modx->getDatabase()
            ->getRow($rs);
        if (!$content) {
            $modx->webAlertAndQuit("No database record has been found for this template.");
        }

        $_SESSION['itemname'] = $content['templatename'];
        if ($content['locked'] == 1 && $_SESSION['mgrRole'] != 1) {
            $modx->webAlertAndQuit(ManagerTheme::getLexicon("error_no_privileges"));
        }
    } else {
        $_SESSION['itemname'] = ManagerTheme::getLexicon("new_template");
        $content['category'] = (int)$_REQUEST['catid'];
    }

    if ($modx->getManagerApi()
        ->hasFormValues()) {
        $modx->getManagerApi()
            ->loadFormValues();
    }

    $content = array_merge($content, $_POST);
    $selectable = $modx->getManagerApi()->action == 19 ? 1 : $content['selectable'];

    // Add lock-element JS-Script
    $lockElementId = $id;
    $lockElementType = 1;
    require_once(MODX_MANAGER_PATH . 'includes/active_user_locks.inc.php');

    include_once(MODX_MANAGER_PATH . 'includes/categories.inc.php');
    $categories = [];
    foreach (getCategories() as $v) {
        $categories[$v['id']] = $v['category'];
    }
    ?>
    @push('scripts.top')
        <script>

          var actions = {
            save: function() {
              documentDirty = false;
              form_save = true;
              document.mutate.save.click();
              //saveWait('mutate');
            },
            duplicate: function() {
              if (confirm("{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}") === true) {
                documentDirty = false;
                document.location.href = "index.php?id=<?= $_REQUEST['id'] ?>&a=96";
              }
            },
            delete: function() {
              if (confirm("{{ ManagerTheme::getLexicon('confirm_delete_template') }}") === true) {
                documentDirty = false;
                document.location.href = 'index.php?id=<?= $_REQUEST['id'] ?>&a=21';
              }
            },
            cancel: function() {
              documentDirty = false;
              document.location.href = 'index.php?a=76';
            }
          };

          document.addEventListener('DOMContentLoaded', function() {
            var h1help = document.querySelector('h1 > .help');
            h1help.onclick = function() {
              document.querySelector('.element-edit-message').classList.toggle('show');
            };
          });

        </script>
    @endpush

    <form name="mutate" method="post" action="index.php">
        <?php
        // invoke OnTempFormPrerender event
        $evtOut = $modx->invokeEvent("OnTempFormPrerender", array("id" => $id));
        if (is_array($evtOut)) {
            echo implode("", $evtOut);
        }
        ?>
        <input type="hidden" name="a" value="20">
        <input type="hidden" name="id" value="<?= $_REQUEST['id'] ?>">
        <input type="hidden" name="mode" value="<?= $modx->getManagerApi()->action ?>">

        <h1>
            <i class="fa fa-newspaper-o"></i><?= ($content['templatename'] ? $content['templatename'] . '<small>(' . $content['id'] . ')</small>' : ManagerTheme::getLexicon('new_template')) ?><i class="fa fa-question-circle help"></i>
        </h1>

        @include('manager::partials.actionButtons', ['select' => '', 'save' => '', 'new' => '1', 'duplicate' => '', 'delete' => '', 'cancel' => ''])

        <div class="container element-edit-message">
            <div class="alert alert-info">{{ ManagerTheme::getLexicon('template_msg') }}</div>
        </div>

        <div class="tab-pane" id="templatesPane">
            <script>
              var tp = new WebFXTabPane(document.getElementById('templatesPane'), {{ get_by_key($modx->config, 'remember_last_tab') ? 1 : 0 }});
            </script>

            <div class="tab-page" id="tabTemplate">
                <h2 class="tab">{{ ManagerTheme::getLexicon('template_edit_tab') }}</h2>
                <script>tp.addTabPage(document.getElementById('tabTemplate'));</script>

                <div class="container container-body">
                    <div class="form-group">
                        @include('manager::form.row', [
                            'for' => 'templatename',
                            'label' => ManagerTheme::getLexicon('template_name'),
                            'small' => $id == $modx->config['default_template'] ? '<b class="text-danger">' . mb_strtolower(rtrim(ManagerTheme::getLexicon('defaulttemplate_title'), ':'), ManagerTheme::getCharset()) . '</b>' : '',
                            'element' => '<div class="form-control-name clearfix">' .
                                ManagerTheme::view('form.inputElement', [
                                    'name' => 'templatename',
                                    'value' => $content['templatename'],
                                    'class' => 'form-control-lg',
                                    'attributes' => 'onchange="documentDirty=true;"'
                                ]) .
                                ($modx->hasPermission('save_role')
                                ? '<label class="custom-control" data-tooltip="' . ManagerTheme::getLexicon('lock_template') . "\n" . ManagerTheme::getLexicon('lock_template_msg') .'">' .
                                 ManagerTheme::view('form.inputElement', [
                                    'type' => 'checkbox',
                                    'name' => 'locked',
                                    'checked' => ($content['locked'] == 1)
                                 ]) .
                                 '<i class="fa fa-lock"></i>
                                 </label>
                                 </div>
                                 <small class="form-text text-danger hide" id="savingMessage"></small>
                                 <script>if (!document.getElementsByName(\'templatename\')[0].value) document.getElementsByName(\'templatename\')[0].focus();</script>'
                                : '')
                        ])

                        @include('manager::form.input', [
                            'name' => 'description',
                            'id' => 'description',
                            'label' => ManagerTheme::getLexicon('template_desc'),
                            'value' => $content['description'],
                            'attributes' => 'onchange="documentDirty=true;" maxlength="255"'
                        ])

                        @include('manager::form.select', [
                            'name' => 'categoryid',
                            'id' => 'categoryid',
                            'label' => ManagerTheme::getLexicon('existing_category'),
                            'value' => $content["category"],
                            'first' => [
                                'text' => ''
                            ],
                            'options' => $categories,
                            'attributes' => 'onchange="documentDirty=true;"'
                        ])

                        @include('manager::form.input', [
                            'name' => 'newcategory',
                            'id' => 'newcategory',
                            'label' => ManagerTheme::getLexicon('new_category'),
                            'value' => (isset($content['newcategory']) ? $content['newcategory'] : ''),
                            'attributes' => 'onchange="documentDirty=true;" maxlength="45"'
                        ])

                    </div>

                    @if($modx->hasPermission('save_role'))
                        <div class="form-group">
                            <label>
                                @include('manager::form.inputElement', [
                                    'name' => 'selectable',
                                    'id' => 'selectable',
                                    'type' => 'checkbox',
                                    'checked' => ($selectable == 1),
                                    'attributes' => 'onchange="documentDirty=true;"'
                                ])
                                {{ ManagerTheme::getLexicon('template_selectable') }}
                            </label>
                        </div>
                    @endif
                </div>

                <!-- HTML text editor start -->
                <div class="navbar navbar-editor">
                    <span>{{ ManagerTheme::getLexicon('template_code') }}</span>
                </div>
                <div class="section-editor clearfix">
                    @include('manager::form.textareaElement', [
                        'name' => 'post',
                        'value' => (isset($content['post']) ? $content['post'] : $content['content']),
                        'class' => 'phptextarea',
                        'rows' => 20,
                        'attributes' => 'onChange="documentDirty=true;"'
                    ])
                </div>
                <!-- HTML text editor end -->

                <input type="submit" name="save" style="display:none">
            </div>

            <?php
            $selectedTvs = array();
            if (!isset($_POST['assignedTv'])) {
                $rs = $modx->getDatabase()
                    ->select(sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category", ManagerTheme::getLexicon('no_category')), sprintf("%s tv
                LEFT JOIN %s tr ON tv.id=tr.tmplvarid
                LEFT JOIN %s cat ON tv.category=cat.id", $modx->getDatabase()
                        ->getFullTableName('site_tmplvars'), $modx->getDatabase()
                        ->getFullTableName('site_tmplvar_templates'), $modx->getDatabase()
                        ->getFullTableName('categories')), "templateid='{$id}'", "tr.rank DESC, tv.rank DESC, tvcaption DESC, tvid DESC"     // workaround for correct sort of none-existing ranks
                    );
                while ($row = $modx->getDatabase()
                    ->getRow($rs)) {
                    $selectedTvs[$row['tvid']] = $row;
                }
                $selectedTvs = array_reverse($selectedTvs, true);       // reverse ORDERBY DESC
            }

            $unselectedTvs = array();
            $rs = $modx->getDatabase()
                ->select(sprintf("tv.name AS tvname, tv.id AS tvid, tr.templateid AS templateid, tv.description AS tvdescription, tv.caption AS tvcaption, tv.locked AS tvlocked, if(isnull(cat.category),'%s',cat.category) AS category, cat.id as catid", ManagerTheme::getLexicon('no_category')), sprintf("%s tv
	    LEFT JOIN %s tr ON tv.id=tr.tmplvarid
	    LEFT JOIN %s cat ON tv.category=cat.id", $modx->getDatabase()
                    ->getFullTableName('site_tmplvars'), $modx->getDatabase()
                    ->getFullTableName('site_tmplvar_templates'), $modx->getDatabase()
                    ->getFullTableName('categories')), "", "category, tvcaption");

            while ($row = $modx->getDatabase()
                ->getRow($rs)) {
                $unselectedTvs[$row['tvid']] = $row;
            }

            // Catch checkboxes if form not validated
            if (isset($_POST['assignedTv'])) {
                $selectedTvs = array();
                foreach ($_POST['assignedTv'] as $tvid) {
                    if (isset($unselectedTvs[$tvid])) {
                        $selectedTvs[$tvid] = $unselectedTvs[$tvid];
                    }
                };
            }

            $total = count($selectedTvs);
            ?>

            <div class="tab-page" id="tabAssignedTVs">
                <h2 class="tab">{{ ManagerTheme::getLexicon('template_assignedtv_tab') }}</h2>
                <script>tp.addTabPage(document.getElementById('tabAssignedTVs'));</script>
                <input type="hidden" name="tvsDirty" id="tvsDirty" value="0">

                <div class="container container-body">
                    @if($total)
                        <p>{{ ManagerTheme::getLexicon['template_tv_msg'] }}</p>
                    @endif

                    @if($modx->hasPermission('save_template') && $total > 1 && $id)
                        {!! sprintf('<div class="form-group"><a class="btn btn-primary" href="index.php?a=117&amp;id=%s">%s</a></div>', $id, ManagerTheme::getLexicon['template_tv_edit']) !!}
                    @endif

                    <?php
                    // Selected TVs
                    $tvList = '';
                    if ($total > 0) {
                        $tvList .= '<ul>';
                        foreach ($selectedTvs as $row) {
                            $desc = !empty($row['tvdescription']) ? '&nbsp;&nbsp;<small>(' . $row['tvdescription'] . ')</small>' : '';
                            $locked = $row['tvlocked'] ? ' <em>(' . ManagerTheme::getLexicon('locked') . ')</em>' : "";
                            $tvList .= sprintf('<li><label><input name="assignedTv[]" value="%s" type="checkbox" checked="checked" onchange="documentDirty=true;jQuery(\'#tvsDirty\').val(\'1\');"> %s <small>(%s)</small> - %s%s</label>%s <a href="index.php?id=%s&a=301&or=%s&oid=%s">%s</a></li>', $row['tvid'], $row['tvname'], $row['tvid'], $row['tvcaption'], $desc, $locked, $row['tvid'], $modx->getManagerApi()->action, $id, ManagerTheme::getLexicon('edit'));
                        }
                        $tvList .= '</ul>';

                    } else {
                        echo ManagerTheme::getLexicon('template_no_tv');
                    }
                    echo $tvList;

                    // Unselected TVs
                    $tvList = '<hr/><p>' . ManagerTheme::getLexicon('template_notassigned_tv') . '</p><ul>';
                    $preCat = '';
                    $insideUl = 0;
                    while ($row = array_shift($unselectedTvs)) {
                        if (isset($selectedTvs[$row['tvid']])) {
                            continue;
                        } // Skip selected
                        $row['category'] = stripslashes($row['category']); //pixelchutes
                        if ($preCat !== $row['category']) {
                            $tvList .= $insideUl ? '</ul>' : '';
                            $tvList .= '<li><strong>' . $row['category'] . ($row['catid'] != '' ? ' <small>(' . $row['catid'] . ')</small>' : '') . '</strong><ul>';
                            $insideUl = 1;
                        }

                        $desc = !empty($row['tvdescription']) ? '&nbsp;&nbsp;<small>(' . $row['tvdescription'] . ')</small>' : '';
                        $locked = $row['tvlocked'] ? ' <em>(' . ManagerTheme::getLexicon('locked') . ')</em>' : "";
                        $tvList .= sprintf('<li><label><input name="assignedTv[]" value="%s" type="checkbox" onchange="documentDirty=true;jQuery(\'#tvsDirty\').val(\'1\');"> %s <small>(%s)</small> - %s%s</label>%s <a href="index.php?id=%s&a=301&or=%s&oid=%s">%s</a></li>', $row['tvid'], $row['tvname'], $row['tvid'], $row['tvcaption'], $desc, $locked, $row['tvid'], $modx->getManagerApi()->action, $id, ManagerTheme::getLexicon('edit'));
                        $tvList .= '</li>';

                        $preCat = $row['category'];
                    }
                    $tvList .= $insideUl ? '</ul>' : '';
                    $tvList .= '</ul>';
                    echo $tvList;

                    ?>
                </div>
            </div>

            <?php
            // invoke OnTempFormRender event
            $evtOut = $modx->invokeEvent("OnTempFormRender", array("id" => $id));
            if (is_array($evtOut)) {
                echo implode("", $evtOut);
            }
            ?>
        </div>
    </form>
@endsection
