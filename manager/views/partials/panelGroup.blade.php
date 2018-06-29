<div class="clearfix"></div>
<div class="panel-group no-transition">
    <div id="{{ $resourceTable }}" class="resourceTable panel panel-default">

    @foreach($elements as $catid => $elList)
        <?php
        // Add panel-heading / category-collapse to output
        $panelGroup .= parsePh($tpl['panelHeading'], array(
            'tab' => $resourceTable,
            'category' => $categories[$catid]['name'],
            'categoryid' => $catid != '' ? ' <small>(' . $catid . ')</small>' : '',
            'catid' => $catid,
        ));

        // Prepare content for panel-collapse
        ?>

        @include('manager::partials.panelCollapse', ['id' => $resourceTable . $catid, 'elements' => $elList])
    @endforeach
    </div>
</div>
<div class="clearfix"></div>

