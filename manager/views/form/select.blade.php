<div class="row form-row form-element-select">
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
        @if(!empty($options))
            <div class="clearfix">
                <select class="form-control" name="{{ $name }}" id="{{ $id or $name }}"
                        {!! $attributes or '' !!}
                >
                    @foreach ($options as $option)
                        @if(isset($option['optgroup']) && !empty($option['optgroup']['options']))
                            <optgroup label="{{ $option['optgroup']['name'] or 'optgroup' }}">
                                @foreach($option['optgroup']['options'] as $opt)
                                    <option value="{{ $opt['value'] }}"
                                            @if($value == $opt['value'])
                                            selected="selected"
                                            @endif
                                    >{{ $opt['text'] or $opt['value'] }}</option>
                                @endforeach
                            </optgroup>
                        @else
                            <option value="{{ $option['value'] }}"
                                    @if($value == $option['value'])
                                    selected="selected"
                                    @endif
                            >{{ $option['text'] or $option['value'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        @endif
        @if($comment)
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
