<div class="tab-page" id="tabVariables">
    <h2 class="tab">
        <i class="fa fa-list-alt"></i> {{ ManagerTheme::getLexicon('tmplvars') }}
    </h2>
    <script type="text/javascript">tpResources.addTabPage(document.getElementById('tabVariables'));</script>
    <div id="tv-info" class="msg-container" style="display:none">
        <div class="element-edit-message-tab">{!! ManagerTheme::getLexicon('tmplvars_management_msg') !!}</div>
        <p class="viewoptions-message">{!! ManagerTheme::getLexicon('view_options_msg') !!}</p>
    </div>

    <div id="_actions">
        <form class="btn-group form-group form-inline">
            <div class="input-group input-group-sm">
                <input type="text" class="form-control filterElements-form" id="site_tmplvars_search" size="30" placeholder="{{ ManagerTheme::getLexicon('element_filter_msg') }}" />
                <div class="input-group-btn">
                    <a class="btn btn-success" href="index.php?a=300">
                        <i class="fa fa-plus-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('new_tmplvars') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="index.php?a=305">
                        <i class="fa fa-sort"></i>
                        <span>{{ ManagerTheme::getLexicon('template_tv_edit') }}</span>
                    </a>
                    <a class="btn btn-secondary" href="javascript:;" id="tv-help">
                        <i class="fa fa-question-circle"></i>
                        <span>{{ ManagerTheme::getLexicon('help') }}</span>
                    </a>
                    <a class="btn btn-secondary switchform-btn" href="javascript:;" data-target="switchForm_site_tmplvars">
                        <i class="fa fa-bars"></i>
                        <span>{{ ManagerTheme::getLexicon('btn_view_options') }}</span>
                    </a>
                </div>
            </div>
        </form>
    </div>

    @include('manager::partials.switchButtons', ['cssId' => 'site_tmplvars'])

    <div class="panel-group">
        <div id="site_tmplvars" class="resourceTable panel panel-default list">
            @foreach($categories as $cat)
                <div class="panel-heading">
                    <span class="panel-title">
                        <a class="accordion-toggle" id="togglesite_tmplvars{{ $cat->id }}" href="#collapsesite_tmplvars{{ $cat->id }}" data-cattype="site_tmplvars" data-catid="{{ $cat->id }}" title="Click to toggle collapse. Shift+Click to toggle all.">
                            <span class="category_name">
                                <strong>{{ $cat->category }}
                                    <small>({{ $cat->id }})</small>
                                </strong>
                            </span>
                        </a>
                    </span>
                </div>
                <div id="collapsesite_tmplvars{{ $cat->id }}" class="panel-collapse collapse in" aria-expanded="true">
                    <ul class="elements">
                        @foreach($cat->tvs as $item)
                            <li>
                                <div class="rTable">
                                    <div class="rTableRow">
                                        @if(!empty($item->rowLock))
                                            <div class="lockCell">
                                                <span title="{{ str_replace(['[+lasthit_df+]', '[+element_type+]'], [$item->rowLock['lasthit_df'], ManagerTheme::getLexicon('lock_element_type_2')], ManagerTheme::getLexicon('lock_element_editing')) }}" class="editResource" style="cursor:context-menu;">
                                                    <i class="fa fa-eye"></i>
                                                </span>&nbsp;
                                            </div>
                                        @endif
                                        <div class="mainCell elements_description">
                                            <span @if(!$item->reltpl)class="disabledPlugin" @endif>
                                            <a class="man_el_name site_tmplvars" data-type="site_tmplvars" data-id="{{ $item->id }}" data-catid="{{ $cat->id }}" href="{{ $item->makeUrl('actions.edit') }}">
                                                {{ $item->name }}
                                                <small>({{ $item->id }})</small>
                                                <span class="elements_descr">
                                                    {{ $item->caption }}
                                                    @if($item->description)
                                                        &nbsp;
                                                        <small>({{ $item->description }})</small>
                                                    @endif
                                                </span>
                                            </a>{{ ManagerTheme::getTextDir('&rlm;') }}
                                            </span>
                                        </div>
                                        <div class="btnCell">
                                            <ul class="elements_buttonbar">
                                                <li>
                                                    <a href="{{ $item->makeUrl('actions.edit') }}" title="{{ ManagerTheme::getLexicon('edit_resource') }}">
                                                        <i class="fa fa-edit fa-fw"></i></a>
                                                </li>
                                                <li>
                                                    <a href="{{ $item->makeUrl('actions.duplicate') }}" title="{{ ManagerTheme::getLexicon('resource_duplicate') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}')">
                                                        <i class="fa fa-clone fa-fw"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ $item->makeUrl('actions.delete') }}" title="{{ ManagerTheme::getLexicon('delete') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_delete') }}')">
                                                        <i class="fa fa-trash fa-fw"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>

    @push('scripts.bot')
        <script>
          initQuicksearch('site_tmplvars_search', 'site_tmplvars');
          initViews('tv', 'tv', 'site_tmplvars');
        </script>
    @endpush
</div>