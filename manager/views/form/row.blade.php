<div class="row form-row @if(isset($type))form-element-{{ $type }}@endif">
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
        <div class="clearfix">
            {!! $element ?? '' !!}
        </div>
        @if(!empty($comment))
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
