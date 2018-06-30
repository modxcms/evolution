<div class="row form-row @if(isset($type))form-element-{{ $type }}@endif">
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
        <div class="clearfix">
            @if(!empty($element))
                {!! $element !!}
            @endif
        </div>
        @if($comment)
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
