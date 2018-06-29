@if( ! is_array($items) || empty($items))
    {{ ManagerTheme::getLexicon('no_results') }}
@else
    <?php
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
