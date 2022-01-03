<?php /** @var EvolutionCMS\Models\SiteModule $item */ ?>
<li>
    <div class="rTable">
        <div class="rTableRow">
            @if(!empty($item->isAlreadyEdit))
                <div class="lockCell">
                    <?php $rowLock = $item->alreadyEditInfo; ?>
                    <span title="{{ str_replace(['[+lasthit_df+]', '[+element_type+]'], [$rowLock['lasthit_df'], ManagerTheme::getLexicon('lock_element_type_2')], ManagerTheme::getLexicon('lock_element_editing')) }}" class="editResource" style="cursor:context-menu;">
                        <i class="{{ $_style['icon_eye'] }}"></i>
                    </span>&nbsp;
                </div>
            @endif
            <div class="mainCell elements_description">
                <span @if($item->disabled)class="disabledPlugin" @endif>
                    @if(empty($action))
                        <span class="man_el_name">
                    @else
                        <a class="man_el_name site_modules" data-type="site_modules" data-id="{{ $item->id }}" data-catid="{{ $item->category }}" href="{{ $item->makeUrl($action) }}">
                    @endif
                        @if(empty($item->icon))
                            <i class="{{ $_style['icon_module'] }}"></i>
                        @else
                            <i class="{{ $item->icon }}"></i>
                        @endif
                        @if($item->locked)
                            <i class="{{ $_style['icon_lock'] }}"></i>
                        @endif
                        {{ $item->name }}
                        <small>({{ $item->id }})</small>
                        <span class="elements_descr">
                            {{ $item->caption }}
                            {!! $item->description !!}
                        </span>
                    @if(empty($action))
                        </span>
                    @else
                        </a>
                    @endif
                    @if(ManagerTheme::getTextDir() !== 'ltr')
                    &rlm;
                    @endif
                </span>
            </div>
            <div class="btnCell">
                <ul class="elements_buttonbar">
                    @if(evolutionCMS()->hasPermission('exec_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.run') }}" title="{{ ManagerTheme::getLexicon('run_module') }}">
                                <i class="{{ $_style['icon_play'] }}"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('edit_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.edit') }}" title="{{ ManagerTheme::getLexicon('edit_resource') }}">
                                <i class="{{ $_style['icon_edit'] }}"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('new_module') && evolutionCMS()->hasPermission('save_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.duplicate') }}" title="{{ ManagerTheme::getLexicon('resource_duplicate') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}')">
                                <i class="{{ $_style['icon_clone'] }}"></i>
                            </a>
                        </li>
                    @endif
                    @if(evolutionCMS()->hasPermission('delete_module'))
                        <li>
                            <a href="{{ $item->makeUrl('actions.delete') }}" title="{{ ManagerTheme::getLexicon('delete') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_delete_module') }}')">
                                <i class="{{ $_style['icon_trash'] }}"></i>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</li>
