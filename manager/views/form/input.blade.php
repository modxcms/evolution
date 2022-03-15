@php
    if(isset($fromFile[$name])) {
        $disabled = 1;
        $comment = '<b>' . ManagerTheme::getLexicon('setting_from_file') . '</b>' . (!empty($comment) ? '<br>' . $comment : '');
        $value = $fromFile[$name];
    }
@endphp

<div class="row form-row form-element-input">
    <label for="{{ $for ?? $name }}" class="control-label col-5 col-md-3 col-lg-2">
        {!! $label ?? '' !!}
        @if(!empty($required))
            <span class="form-element-required">*</span>
        @endif
        @if(!empty($small))
            <small class="form-text text-muted">{!! $small !!}</small>
        @endif
    </label>
    <div class="col-7 col-md-9 col-lg-10">
        <input class="form-control" type="{{ $type ?? 'text' }}"
            @if(!empty($id)) id="{{ $id }}" @elseif(!empty($name)) id="{{ $name }}" @endif
        @if(!empty($name)) name="{{ $name }}" @endif
        value="{{ $value ?? '' }}"
        @if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
        {!! $attributes ?? '' !!}
        @if(!empty($readonly)) readonly @endif
        @if(!empty($disabled)) disabled @endif
        />
        @if(!empty($comment))
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
<div class="split my-1"></div>
