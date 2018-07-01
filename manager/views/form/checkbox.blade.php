<div class="row form-row form-element-checkbox">
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
        @if(!empty($options))
            @foreach ($options as $key => $option)
                <div class="checkbox">
                    <label>
                        @if(is_string($option))
                            <input type="checkbox" name="{{ $name }}" value="{{ $key }}"
                                    @if($value == $key) checked="checked" @endif
                            />
                            {!! $option !!}
                        @else
                            <input type="checkbox" name="{{ $name }}[]" value="{{ $option['value'] }}"
                                    {!! $option['attributes'] or '' !!}
                                    @if($value == $option['value']) checked="checked" @endif
                            @if($disabled || $option['disabled']) disabled @endif
                            />
                            {!! $option['text'] !!}
                        @endif
                    </label>
                </div>
            @endforeach
        @endif
        @if($comment)
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
