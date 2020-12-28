<div class="tab-page {{ $tabPageName }}" id="{{ $tabIndexPageName }}">
    <h2 class="tab">
        <a href="?a=86&tab={{ $tab->getIndex() }}"><i
                    class="{{ $_style['icon_category'] }}"></i>{{ ManagerTheme::getLexicon('category_heading') }}</a>
    </h2>
    <script>tpResources.addTabPage(document.getElementById('{{ $tabIndexPageName }}'));</script>

    <div class="form-group">
        <a class="btn btn-secondary btn-sm" href="{{ (new EvolutionCMS\Models\PermissionsGroups)->makeUrl('actions.new') }}">
            <i class="{{ $_style['icon_add'] }} hide4desktop"></i> {{ ManagerTheme::getLexicon('new_category') }}
        </a>
    </div>
    <div class="form-group">
        @if($groups->count() === 0)
            <p>{{ ManagerTheme::getLexicon('no_records_found') }}</p>
        @else
            <div class="row">
                <div class="table-responsive">
                    <table class="table data">
                        <thead>
                        <tr>
                            <td>{{ ManagerTheme::getLexicon('category_heading') }}</td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php /** @var EvolutionCMS\Models\PermissionsGroups $role */ ?>
                        @foreach($groups as $group)
                            <tr>
                                <td>
                                    <a class="text-primary" href="{{ $group->makeUrl('actions.edit') }}">
                                        {{ManagerTheme::getLexicon($group->lang_key, $group->name ) }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
    <div class="clearfix"></div>
</div>

@push('scripts.bot')
    <script>
        initQuicksearch('{{ $tabIndexPageName }}_search', '{{ $tabIndexPageName }}_content');
        initViews('ch', '{{ $tabIndexPageName }}', '{{ $tabIndexPageName }}_content');
    </script>
@endpush
