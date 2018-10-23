<?php /** @var EvolutionCMS\Models\SitePlugin $item */ ?>
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
                    <a class="man_el_name" data-type="{{ $tabIndexPageName }}" data-id="{{ $item->id }}" data-catid="{{ $item->category }}" href="{{ $item->makeUrl('actions.edit') }}">
                        <i class="fa fa-plug"></i>
                        @if($item->locked)
                            <i class="fa fa-lock"></i>
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
                            <i class="fa fa-edit fa-fw"></i>
                        </a>
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
