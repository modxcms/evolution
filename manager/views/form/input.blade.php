<div class="row form-row form-element-input">
    <label for="{{ $name }}" class="control-label col-5 col-md-3 col-lg-2">
        {!! $label !!}
        @if($required)
            <span class="form-element-required">*</span>
        @endif
        @if($small)
            <small class="form-text text-muted">{!! $small !!}</small>
        @endif
    </label>
    <div class="col-7 col-md-9 col-lg-10">
        <input class="form-control" type="{{ $type or 'text' }}" id="{{ $id or $name }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
                {!! $attributes or '' !!}
                @if($readonly) readonly @endif
        @if($disabled) disabled @endif
        />
        @if($comment)
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
