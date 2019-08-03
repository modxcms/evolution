<?php /** @var EvolutionCMS\Models\SiteTemplate $item */ ?>
<li>
    <div class="rTable">
        <div class="rTableRow">
            @if(!empty($item->isAlreadyEdit))
                <div class="lockCell">
                    <?php $rowLock = $item->alreadyEditInfo; ?>
                    <span title="{{ str_replace(['[+lasthit_df+]', '[+element_type+]'], [$rowLock['lasthit_df'], ManagerTheme::getLexicon('lock_element_type_2')], ManagerTheme::getLexicon('lock_element_editing')) }}" class="editResource" style="cursor:context-menu;">
                        <i class="{{ $_style['actions_preview'] }}"></i>
                    </span>&nbsp;
                </div>
            @endif
            <div class="mainCell elements_description">
                <span>
                    <a class="man_el_name" data-type="{{ $tabIndexPageName }}" data-id="{{ $item->id }}" data-catid="{{ $item->category }}" href="{{ $item->makeUrl('actions.edit') }}">
                        <i class="{{ $_style['icons_template'] }}"></i>
                        @if($item->locked)
                            <i class="{{ $_style['icons_lock'] }}"></i>
                        @endif
                        {{ $item->name }}
                        <small>({{ $item->id }})</small>
                        <span class="elements_descr">
                            {{ $item->caption }}
                            {!! $item->description !!}
                        </span>
                    </a>
                    @if(ManagerTheme::getTextDir() !== 'ltr')
                        &rlm;
                    @endif
                </span>
            </div>
            <div class="btnCell">
                <ul class="elements_buttonbar">
                    <li>
                        <a href="{{ $item->makeUrl('actions.edit') }}" title="{{ ManagerTheme::getLexicon('edit_resource') }}">
                            <i class="{{ $_style['actions_edit'] }}"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $item->makeUrl('actions.duplicate') }}" title="{{ ManagerTheme::getLexicon('resource_duplicate') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}')">
                            <i class="{{ $_style['actions_duplicate'] }}"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $item->makeUrl('actions.delete') }}" title="{{ ManagerTheme::getLexicon('delete') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_delete') }}')">
                            <i class="{{ $_style['actions_delete'] }}"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</li>
