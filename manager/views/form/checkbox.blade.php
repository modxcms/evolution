<div class="row form-row form-element-checkbox">
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
        @if(!empty($options))
            @foreach ($options as $key => $option)
                <div class="checkbox">
                    <label>
                        @if(is_string($option))
                            <input type="checkbox" name="{{ $name }}" value="{{ $key ?? '' }}"
                                @if(isset($value) && $value == $key) checked="checked" @endif
                            />
                            {!! $option ?? '' !!}
                        @else
                            <input type="checkbox" name="{{ $name }}[]" value="{{ $option['value'] ?? $key }}"
                                {!! $option['attributes'] ?? '' !!}
                                @if(isset($value) && ((isset($option['value']) && $value == $option['value']) || ($value == $key))) checked="checked" @endif
                            @if(!empty($disabled) || !empty($option['disabled'])) disabled @endif
                            />
                            {!! $option['text'] ?? '' !!}
                        @endif
                    </label>
                </div>
            @endforeach
        @endif
        @if(!empty($comment))
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
