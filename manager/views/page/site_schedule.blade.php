@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script src="media/script/tablesort.js"></script>
    @endpush
    <h1>
        <i class="fa fa-calendar"></i>{{ ManagerTheme::getLexicon('site_schedule') }}
    </h1>

    <div class="tab-page">
        <div class="container container-body">
            <div class="form-group" id="lyr1">
                <b>{{ ManagerTheme::getLexicon('publish_events') }}</b>
                <?php
                $rs = $modx->getDatabase()->select('id, pagetitle, pub_date', $modx->getDatabase()->getFullTableName('site_content'), "pub_date > " . time() . "", 'pub_date ASC');
                $limit = $modx->getDatabase()->getRecordCount($rs);
                if($limit < 1) {
                ?>
                <p>{{ ManagerTheme::getLexicon('no_docs_pending_publishing') }}</p>
                <?php
                } else {
                ?>
                <div class="table-responsive">
                    <table class="grid sortabletable" id="table-1">
                        <thead>
                        <tr>
                            <th class="sortable" style="width: 1%">{{ ManagerTheme::getLexicon('id') }}</th>
                            <th class="sortable">{{ ManagerTheme::getLexicon('resource') }}</th>
                            <th class="sortable text-right" style="width: 15%">{{ ManagerTheme::getLexicon('publish_date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($row = $modx->getDatabase()->getRow($rs)) {
                        ?>
                        <tr>
                            <td class="text-right"><?= $row['id'] ?></td>
                            <td><a href="index.php?a=3&id=<?= $row['id'] ?>"><?= $row['pagetitle'] ?></a></td>
                            <td class="text-nowrap text-right"><?= $modx->toDateFormat($row['pub_date'] + $server_offset_time) ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="form-group" id="lyr2">
                <b>{{ ManagerTheme::getLexicon('unpublish_events') }}</b>
                <?php
                $rs = $modx->getDatabase()->select('id, pagetitle, unpub_date', $modx->getDatabase()->getFullTableName('site_content'), "unpub_date > " . time() . "", 'unpub_date ASC');
                $limit = $modx->getDatabase()->getRecordCount($rs);
                if($limit < 1) {
                ?>
                <p>{{ ManagerTheme::getLexicon('no_docs_pending_unpublishing') }}</p>
                <?php
                } else {
                ?>
                <div class="table-responsive">
                    <table class="grid sortabletable" id="table-2">
                        <thead>
                        <tr>
                            <th class="sortable" style="width: 1%">{{ ManagerTheme::getLexicon('id') }}</th>
                            <th class="sortable">{{ ManagerTheme::getLexicon('resource') }}</th>
                            <th class="sortable text-right" style="width: 15%">{{ ManagerTheme::getLexicon('unpublish_date') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($row = $modx->getDatabase()->getRow($rs)) {
                        ?>
                        <tr>
                            <td class="text-right"><?= $row['id'] ?></td>
                            <td><a href="index.php?a=3&id=<?= $row['id'] ?>"><?= $row['pagetitle'] ?></a></td>
                            <td class="text-nowrap text-right"><?= $modx->toDateFormat($row['unpub_date'] + $server_offset_time) ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                ?>
            </div>
            <div class="form-group">
                <b>{{ ManagerTheme::getLexicon('all_events') }}</b>
                <?php
                $rs = $modx->getDatabase()->select('id, pagetitle, pub_date, unpub_date', $modx->getDatabase()->getFullTableName('site_content'), "pub_date > 0 OR unpub_date > 0", "pub_date DESC");
                $limit = $modx->getDatabase()->getRecordCount($rs);
                if($limit < 1) {
                ?>
                <p>{{ ManagerTheme::getLexicon('no_docs_pending_pubunpub') }}</p>
                <?php
                } else {
                ?>
                <div class="table-responsive">
                    <table class="grid sortabletable" id="table-3">
                        <thead>
                        <tr>
                            <th class="sortable" style="width: 1%"><b>{{ ManagerTheme::getLexicon('id') }}</b></th>
                            <th class="sortable"><b>{{ ManagerTheme::getLexicon('resource') }}</b></th>
                            <th class="sortable text-right" style="width: 15%"><b>{{ ManagerTheme::getLexicon('publish_date') }}</b></th>
                            <th class="sortable text-right" style="width: 15%"><b>{{ ManagerTheme::getLexicon('unpublish_date') }}</b></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        while($row = $modx->getDatabase()->getRow($rs)) {
                        ?>
                        <tr>
                            <td class="text-right"><?= $row['id'] ?></td>
                            <td><a href="index.php?a=3&id=<?= $row['id'] ?>"><?= $row['pagetitle'] ?></a></td>
                            <td class="text-nowrap text-right"><?= $row['pub_date'] == 0 ? "" : $modx->toDateFormat($row['pub_date'] + $server_offset_time) ?></td>
                            <td class="text-nowrap text-right"><?= $row['unpub_date'] == 0 ? "" : $modx->toDateFormat($row['unpub_date'] + $server_offset_time) ?></td>
                        </tr>
                        <?php
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
@endsection