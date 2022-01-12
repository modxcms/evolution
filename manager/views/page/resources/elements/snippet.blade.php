<?php /** @var EvolutionCMS\Models\SiteSnippet $item */ ?>
<li>
    <div class="rTable">
        <div class="rTableRow">
            @if(!empty($item->isAlreadyEdit))
                <div class="lockCell">
                    <?php $rowLock = $item->alreadyEditInfo; ?>
                    <span title="{{ str_replace(['[+lasthit_df+]', '[+element_type+]'], [$rowLock['lasthit_df'],  ManagerTheme::getLexicon('lock_element_type_2')], ManagerTheme::getLexicon('lock_element_editing')) }}" class="editResource" style="cursor:context-menu;">
                        <i class="{{ $_style['icon_eye'] }}"></i>
                    </span>&nbsp;
                </div>
            @endif
            <div class="mainCell elements_description">
                <span @if($item->disabled)class="disabledPlugin" @endif>
                    <a class="man_el_name site_snippets" target="main" data-type="site_snippets" data-id="{{ $item->id }}" data-catid="{{ $item->category }}" href="{{ $item->makeUrl('actions.edit') }}">
                        <i class="{{ $_style['icon_code'] }}"></i>
                        @if($item->locked)
                            <i class="{{ $_style['icon_lock']  }}"></i>
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
                            <i class="{{ $_style['icon_edit'] }}"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $item->makeUrl('actions.duplicate') }}" title="{{ ManagerTheme::getLexicon('resource_duplicate') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_duplicate_record') }}')">
                            <i class="{{ $_style['icon_clone'] }}"></i>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $item->makeUrl('actions.delete') }}" title="{{ ManagerTheme::getLexicon('delete') }}" onclick="return confirm('{{ ManagerTheme::getLexicon('confirm_delete_snippet') }}')">
                            <i class="{{ $_style['icon_trash'] }}"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</li>
