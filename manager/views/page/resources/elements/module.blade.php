<?php /** @var EvolutionCMS\Models\SiteModule $item */ ?>
<li>
    <div class="rTable">
        <div class="rTableRow">
            @if(!empty($item->isAlreadyEdit))
                <div class="lockCell">
                    <?php $rowLock = $item->alreadyEditInfo; ?>
                    <span title="{{ str_replace(['[+lasthit_df+]', '[+element_type+]'], [$rowLock['lasthit_df'], ManagerTheme::getLexicon('lock_element_type_2')], ManagerTheme::getLexicon('lock_element_editing')) }}" class="editResource" style="cursor:context-menu;">
                        <i class="fa fa-eye"></i>
                    </span>&nbsp;
                </div>
            @endif
            <div class="mainCell elements_description">
                <span @if($item->disabled)class="disabledPlugin" @endif>
                    @if($action !== '')
                        <a class="man_el_name {{ $tabName }}" data-type="{{ $tabName }}" data-id="{{ $item->id }}" data-catid="{{ $cat->id }}" href="{{ $item->makeUrl($action) }}">
                    @else
                        <span class="man_el_name">
                    @endif
                        <i class="fa fa-cube"></i>
                        @if($item->locked)
                            <i class="fa fa-lock"></i>
                        @endif
                        {{ $item->name }}
                        <small>({{ $item->id }})</small>
                        <span class="elements_descr">
                            {{ $item->caption }}
                            {!! $item->description !!}
                        </span>
                    @if($action !== '')
                        </a>
                    @else
                        </span>
                    @endif
                    {{ ManagerTheme::getTextDir('&rlm;') }}
                </span>
            </div>
            <div class="btnCell">
                <ul class="elements_buttonbar">
                    @if(evolutionCMS()->hasPermission('exec_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.run') }}" title="{{ ManagerTheme::getLexicon('run_module') }}">
                                <i class="fa fa-play fa-fw"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('edit_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.edit') }}" title="{{ ManagerTheme::getLexicon('edit_resource') }}">
                                <i class="fa fa-edit fa-fw"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('new_module') && evolutionCMS()->hasPermission('save_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.duplicate') }}" title="{{ ManagerTheme::getLexicon('resource_duplicate') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}')">
                                <i class="fa fa-clone fa-fw"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('delete_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.delete') }}" title="{{ ManagerTheme::getLexicon('delete') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_delete') }}')">
                                <i class="fa fa-trash fa-fw"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</li>
