@extends('manager::template.page')
@section('content')
    <?php
    // Get table Names (alphabetical)
    $tbl_event_log = $modx->getDatabase()->getFullTableName('event_log');
    $tbl_manager_users = $modx->getDatabase()->getFullTableName('manager_users');
    $tbl_web_users = $modx->getDatabase()->getFullTableName('web_users');

    // initialize page view state - the $_PAGE object
    $modx->getManagerApi()->initPageViewState();
    $_PAGE = [
        'vs' => []
    ];

    // get and save search string
    if (get_by_key($_REQUEST, 'op') === 'reset') {
        $sqlQuery = $query = '';
        $_PAGE['vs']['search'] = '';
    } else {
        $sqlQuery = $query = isset($_REQUEST['search']) ? $_REQUEST['search'] : get_by_key($_PAGE, 'vs.search');
        if (!is_numeric($sqlQuery)) {
            $sqlQuery = $modx->getDatabase()->escape($query);
        }
        $_PAGE['vs']['search'] = $query;
    }

    // get & save listmode
    $listmode = isset($_REQUEST['listmode']) ? $_REQUEST['listmode'] : get_by_key($_PAGE, 'vs.lm');
    $_PAGE['vs']['lm'] = $listmode;

    // context menu
    $cm = new \EvolutionCMS\Support\ContextMenu("cntxm", 150);
    $cm->addItem(ManagerTheme::getLexicon('view_log'), "js:menuAction(1)", $_style['icon_eye']);
    $cm->addSeparator();
    $cm->addItem(ManagerTheme::getLexicon('delete'), "js:menuAction(2)", $_style['icon_trash'], (!$modx->hasPermission('delete_eventlog') ? 1 : 0));
    echo $cm->render();
    ?>
    @push('scripts.top')
        <script type="text/javascript">
          function searchResource()
          {
            document.resource.op.value = 'srch';
            document.resource.submit();
          };

          function resetSearch()
          {
            document.resource.search.value = '';
            document.resource.op.value = 'reset';
            document.resource.submit();
          };

          function changeListMode()
          {
            var m = parseInt(document.resource.listmode.value) ? 1 : 0;
            if (m) {
              document.resource.listmode.value = 0;
            } else {
              document.resource.listmode.value = 1;
            }
            document.resource.submit();
          };

          var selectedItem;
          var contextm = '{{ $cm->getClientScriptObject() }}';

          function showContentMenu(id, e)
          {
            selectedItem = id;
            contextm.style.left = (e.pageX || (e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft))) + 'px';
            contextm.style.top = (e.pageY || (e.clientY + (document.documentElement.scrollTop || document.body.scrollTop))) + 'px';
            contextm.style.visibility = 'visible';
            e.cancelBubble = true;
            return false;
          };

          function menuAction(a)
          {
            var id = selectedItem;
            switch (a) {
              case 1:		// view log details
                window.location.href = 'index.php?a=115&id=' + id;
                break;
              case 2:		// clear log
                window.location.href = 'index.php?a=116&id=' + id;
                break;
            }
          }

          document.addEventListener('click', function() {
            contextm.style.visibility = 'hidden';
          });

          document.addEventListener('DOMContentLoaded', function() {
            var h1help = document.querySelector('h1 > .help');
            h1help.onclick = function() {
              document.querySelector('.element-edit-message').classList.toggle('show');
            };
          });
        </script>
    @endpush

    <form name="resource" method="post">
        <input type="hidden" name="id" value="{{ get_by_key($_REQUEST, 'id') }}" />
        <input type="hidden" name="listmode" value="{{ $listmode }}" />
        <input type="hidden" name="op" value="" />

        <h1>
            <i class="{{ $_style['icon_info_triangle'] }}"></i>{{ ManagerTheme::getLexicon('eventlog_viewer') }}<i class="{{ $_style['icon_question_circle'] }} help"></i>
        </h1>

        <div class="container element-edit-message">
            <div class="alert alert-info">{{ ManagerTheme::getLexicon('eventlog_msg') }}</div>
        </div>

        <div class="tab-page">
            <!-- load modules -->
            <div class="container container-body">
                <div class="row searchbar form-group">
                    <div class="col-sm-6 input-group">
                        <div class="input-group-btn">
                            <a class="btn btn-danger btn-sm" href="index.php?a=116&cls=1"><i class="{{ $_style['icon_trash'] }}"></i> {{ ManagerTheme::getLexicon('clear_log') }}</a>
                        </div>
                    </div>
                    <div class="col-sm-6 ">
                        <div class="input-group float-right w-auto">
                            <input class="form-control form-control-sm" name="search" type="text" value="<?= $query ?>" placeholder="{{ ManagerTheme::getLexicon('search') }}" />
                            <div class="input-group-append">
                                <a class="btn btn-secondary btn-sm" href="javascript:;" title="{{ ManagerTheme::getLexicon('search') }}" onclick="searchResource();return false;"><i class="{{ $_style['icon_search'] }}"></i></a>
                                <a class="btn btn-secondary btn-sm" href="javascript:;" title="{{ ManagerTheme::getLexicon('reset') }}" onclick="resetSearch();return false;"><i class="{{ $_style['icon_refresh'] }}"></i></a>
                                <a class="btn btn-secondary btn-sm" href="javascript:;" title="{{ ManagerTheme::getLexicon('list_mode') }}" onclick="changeListMode();return false;"><i class="{{ $_style['icon_table'] }}"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="table-responsive">
                        <?php
                        $ds = $modx->getDatabase()->select("el.id, ELT(el.type , 'text-info " . $_style['icon_info_circle'] . "', 'text-warning " . $_style['icon_info_triangle'] . "' , 'text-danger " . $_style['icon_cancel'] . "' ) as icon, el.createdon, el.source, el.eventid,IFNULL(wu.username,mu.username) as username", "{$tbl_event_log} AS el
			LEFT JOIN {$tbl_manager_users} AS mu ON mu.id=el.user AND el.usertype=0
			LEFT JOIN {$tbl_web_users} AS wu ON wu.id=el.user AND el.usertype=1", ($sqlQuery ? "" . (is_numeric($sqlQuery) ? "(eventid='{$sqlQuery}') OR " : '') . "(source LIKE '%{$sqlQuery}%') OR (description LIKE '%{$sqlQuery}%')" : ""), "createdon DESC");
                        $grd = new \EvolutionCMS\Support\DataGrid('', $ds, 100); // set page size to 0 t show all items
                        $grd->pagerClass = '';
                        $grd->pageClass = 'page-item';
                        $grd->selPageClass = 'page-item active';
                        $grd->noRecordMsg = ManagerTheme::getLexicon('no_records_found');
                        $grd->cssClass = "table data nowrap";
                        $grd->columnHeaderClass = "tableHeader";
                        $grd->itemClass = "tableItem";
                        $grd->altItemClass = "tableAltItem";
                        $grd->fields = "type,source,createdon,eventid,username";
                        $grd->columns = ManagerTheme::getLexicon('type') . " ," . ManagerTheme::getLexicon('source') . " ," . ManagerTheme::getLexicon('date') . " ," . ManagerTheme::getLexicon('event_id') . " ," . ManagerTheme::getLexicon('sysinfo_userid');
                        $grd->colWidths = "1%,,1%,1%,1%";
                        $grd->colAligns = "center,,,center,center";
                        $grd->colTypes = "template:<a class='gridRowIcon' href='javascript:;' onclick='return showContentMenu([+id+],event);' title='" . ManagerTheme::getLexicon('click_to_context') . "'><i class='[+icon+]'></i></a>||template:<a href='index.php?a=115&id=[+id+]' title='" . ManagerTheme::getLexicon('click_to_view_details') . "'>[+source+]</a>||date: " . $modx->toDateFormat(null, 'formatOnly') . ' %I:%M %p';
                        if ($listmode == '1') {
                            $grd->pageSize = 0;
                        }
                        if (get_by_key($_REQUEST, 'op') === 'reset') {
                            $grd->pageNumber = 1;
                        }
                        // render grid
                        echo $grd->render();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
