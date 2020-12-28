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
        @if(isset($value)) value="{{ $value }}" @endif
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
