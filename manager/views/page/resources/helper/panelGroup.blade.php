<div class="clearfix"></div>
<div class="panel-group no-transition">
    <div id="{{ $tabName }}" class="resourceTable panel panel-default">
        @foreach($categories as $cat)
            @include('manager::page.resources.helper.panelHeading', [
                'catid' => $cat->id,
                'category' => $cat->category
             ])
            @include('manager::page.resources.helper.panelCollapse', [
                'catid' => $cat->id,
                'cat' => $cat
            ])
        @endforeach
    </div>
</div>
<div class="clearfix"></div>
