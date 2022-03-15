@php
    if(isset($fromFile[$name])) {
        $disabled = 1;
        $comment = '<b>' . ManagerTheme::getLexicon('setting_from_file') . '</b>' . (!empty($comment) ? '<br>' . $comment : '');
        $value = $fromFile[$name];
    }
@endphp

<input class="form-control {{ $class ?? '' }}" type="{{ $type ?? 'text' }}"
    @if(!empty($id)) id="{{ $id }}" @elseif(!empty($name)) id="{{ $name }}" @endif
@if(!empty($name)) name="{{ $name }}" @endif
@if(isset($value)) value="{{ $value }}" @endif
@if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
@if(!empty($checked)) checked @endif
@if(!empty($readonly)) readonly @endif
@if(!empty($disabled)) disabled @endif
    {!! $attributes ?? '' !!}
/>
