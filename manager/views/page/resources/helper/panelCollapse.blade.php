<div id="collapse{{ $tabName }}{{ $catid }}" class="panel-collapse collapse in" aria-expanded="true">
    <ul class="elements">
        @foreach($cat->tvs as $item)
            @include('manager::page.resources.helper.element', [
                'item' => $item
            ])
        @endforeach
    </ul>
</div>
