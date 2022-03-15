@php
    if(isset($fromFile[$name])) {
        $disabled = 1;
        $comment = '<b>' . ManagerTheme::getLexicon('setting_from_file') . '</b>' . (!empty($comment) ? '<br>' . $comment : '');
        $value = $fromFile[$name];
    }
@endphp

<textarea class="form-control {{ $class ?? '' }}" name="{{ $name }}" id="{{ $id ?? $name }}" rows="{{ $rows ?? '3' }}"
    @if(!empty($placeholder)) placeholder="{{ $placeholder }}" @endif
{!! $attributes ?? '' !!}
@if(!empty($readonly)) readonly @endif
@if(!empty($disabled)) disabled @endif
>{{ $value }}</textarea>
