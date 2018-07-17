<div class="row form-row form-element-textarea">
    <label for="{{ $for or $name }}" class="control-label col-5 col-md-3 col-lg-2">
        {!! $label or '' !!}
        @if(!empty($required))
            <span class="form-element-required">*</span>
        @endif
        @if(!empty($small))
            <small class="form-text text-muted">{!! $small !!}</small>
        @endif
    </label>
    <div class="col-7 col-md-9 col-lg-10">
        <textarea class="form-control" name="{{ $name }}" id="{{ $id or $name }}" rows="{{ $rows or '3' }}"
            @if(!empty($placeholder)) placeholder="{{ $placeholder }}" @endif
        {!! $attributes or '' !!}
        @if(!empty($readonly)) readonly @endif
        >{{ $value }}</textarea>
        @if(!empty($comment))
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
