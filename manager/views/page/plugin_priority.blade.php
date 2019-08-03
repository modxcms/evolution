@extends('manager::template.page')
@section('content')
    @push('scripts.top')
        <script type="text/javascript">
            var actions = {
                save: function() {
                    var el = document.getElementById('updated');
                    if (el) {
                        el.style.display = 'none';
                    }
                    el = document.getElementById('updating');
                    if (el) {
                        el.style.display = 'block';
                    }
                    setTimeout('document.sortableListForm.submit()', 1000);
                }, cancel: function() {
                    window.location.href = 'index.php?a=76';
                },
            };
        </script>
    @endpush

    <h1>
        <i class="{{ $_style['actions_sort_num_asc'] }}"></i>{{ ManagerTheme::getLexicon('plugin_priority_title') }}
    </h1>

    {!! ManagerTheme::getStyle('actionbuttons.dynamic.save') !!}

    <div class="tab-page">
        <div class="container container-body">
            <b>{{ ManagerTheme::getLexicon('plugin_priority') }}</b>
            <p>{{ ManagerTheme::getLexicon('plugin_priority_instructions') }}</p>

            @if($updateMsg)
                <span class="text-success" id="updated">{{ ManagerTheme::getLexicon('sort_updated') }}</span>
            @endif

            <span class="text-danger" style="display:none;" id="updating">{{ ManagerTheme::getLexicon('sort_updating') }}</span>

            @foreach($events as $event)
                <div class="form-group clearfix">
                    <strong>{{ $event->name }}</strong>
                    <ul id="{{ $event->getKey() }}" class="sortableList">
                        @foreach($event->plugins as $plugin)
                            <li id="item_{{ $plugin->getKey() }}"@if($plugin->disabled) class="disabledPlugin"@endif>
                                <i class="{{ $_style['icons_plugin'] }}"></i> {{ $plugin->name }}@if($plugin->disabled) (hide) @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    <form action="" method="post" name="sortableListForm">
        <input type="hidden" name="listSubmitted" value="true" />
        @foreach ($events->pluck('id') as $eventId)
            <input type="hidden" id="list_{{ $eventId }}" name="list_{{ $eventId }}" value="" />
        @endforeach
    </form>

    <script type="text/javascript">
        evo.sortable('.sortableList > li', {
            complete: function(a) {
                let list = [];
                for (let i = 0; i < a.parentNode.childNodes.length; i++) {
                    list.push(a.parentNode.childNodes[i].id);
                }
                document.getElementById('list_' + a.parentNode.id).value = list.join(',');
            },
        });
    </script>

@endsection
