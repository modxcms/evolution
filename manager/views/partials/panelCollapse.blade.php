<div id="collapse{{ $id }}" class="panel-collapse collapse in" aria-expanded="true">
    @if(is_array($elList))
        <ul class="elements">
            @foreach($elements as $element)
                <li>
                    <div class="rTable">
                        <div class="rTableRow">
                            {{ $element['lockedByUser'] }}
                            <div class="mainCell elements_description">
                                <span{{ $element['class'] }}>
                                <a class="man_el_name {{ $resourceTable }}" data-type="{{ $element['resourceTable'] }}" data-id="{{ $element['id'] }}" data-catid="{{ $element['catid'] }}" href="index.php?a={{ $element['actionEdit'] }}&id={{ $element['id'] }}">
                                    {{ $element['name'] }} <small>({{ $element['id'] }})</small> <span class="elements_descr">{{ $element['caption'] }}</span>
                                </a>{{ $element['textdir'] }}
                                </span>
                            </div>
                            {!! $element['buttons'] !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @else
        {{ $elements }}
    @endif
</div>
