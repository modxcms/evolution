<div class="row form-row form-element-textarea">
    <label for="{{ $for or $name }}" class="control-label col-5 col-md-3 col-lg-2">
        {!! $label !!}
        @if($required)
            <span class="form-element-required">*</span>
        @endif
        @if($small)
            <small class="form-text text-muted">{!! $small !!}</small>
        @endif
    </label>
    <div class="col-7 col-md-9 col-lg-10">
        <textarea class="form-control" name="{{ $name }}" id="{{ $id or $name }}" rows="{{ $rows or '3' }}" placeholder="{{ $placeholder }}"
                {!! $attributes or '' !!}
                @if($readonly) readonly @endif
        >{!! $value !!}</textarea>
        @if($comment)
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
