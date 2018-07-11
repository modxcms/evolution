<div class="row form-row form-element-select">
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
        @if(!empty($options) || !empty($first))
            <div class="clearfix">
                <select class="form-control" name="{{ $name }}" id="{{ $id or $name }}"
                    {!! $attributes or '' !!}
                >
                    @if(!empty($first))
                        <option value="{{ $first['value'] or '' }}">{{ $first['text'] or '' }}</option>
                    @endif
                    @if(!empty($options))
                        @foreach ($options as $key => $option)
                            @if(isset($option['optgroup']) && !empty($option['optgroup']['options']))
                                <optgroup label="{{ $option['optgroup']['name'] or 'optgroup' }}">
                                    @foreach($option['optgroup']['options'] as $k => $opt)
                                        @if(is_string($opt))
                                            <option value="{{ $k }}"
                                                @if(isset($value) && $value == $k)
                                                selected="selected"
                                                @endif
                                            >{{ $opt }}</option>
                                        @else
                                            <option value="{{ $opt['value'] or $k }}"
                                                @if(isset($value) && $value == $opt['value'])
                                                selected="selected"
                                                @endif
                                            >{{ $opt['text'] or $opt['value'] }}</option>
                                        @endif
                                    @endforeach
                                </optgroup>
                            @elseif(is_string($option))
                                @if(!empty($as))
                                    @if($as == 'keys')
                                        <option value="{{ $key }}"
                                            @if(isset($value) && $value == $key)
                                            selected="selected"
                                            @endif
                                        >{{ $key }}</option>
                                    @elseif($as == 'values')
                                        <option value="{{ $option }}"
                                            @if(isset($value) && $value == $option)
                                            selected="selected"
                                            @endif
                                        >@if(!empty($ucwords)){{ ucwords(str_replace("_", " ", $option)) }}@else{{ $option }}@endif</option>
                                    @endif
                                @else
                                    <option value="{{ $key }}"
                                        @if(isset($value) && $value == $key)
                                        selected="selected"
                                        @endif
                                    >@if(!empty($ucwords)){{ ucwords(str_replace("_", " ", $option)) }}@else{{ $option }}@endif</option>
                                @endif
                            @else
                                <option value="{{ $option['value'] }}"
                                    @if(isset($value) && $value == $option['value'])
                                    selected="selected"
                                    @endif
                                >{{ $option['text'] or $option['value'] }}</option>
                            @endif
                        @endforeach
                    @endif
                </select>
            </div>
        @endif
        @if(!empty($comment))
            <small class="form-text text-muted">{!! $comment !!}</small>
        @endif
    </div>
</div>
