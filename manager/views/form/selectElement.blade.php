@if(!empty($options) || !empty($first))
    <select class="form-control {{ $class or '' }}" name="{{ $name }}" id="{{ $id or $name }}"
        {!! $attributes or '' !!}
    >
        @if(!empty($first))
            <option value="{{ $first['value'] or '' }}">{{ $first['text'] or '' }}</option>
        @endif
        @if(!empty($options))
            @foreach ($options as $key => $option)
                @if(isset($option['optgroup']) && !empty($option['optgroup']['options']))
                    <optgroup label="{{ $option['optgroup']['name'] or 'optgroup' }}">
                        @foreach($option['optgroup']['options'] as $opt)
                            <option value="{{ $opt['value'] }}" @if(isset($value) && $value == $opt['value'])selected="selected"@endif>{{ $opt['text'] }}</option>
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
                    <option value="{{ $option['value'] or ''}}"
                        @if(isset($value) && $value == $option['value'])
                        selected="selected"
                        @endif
                    >{{ $option['text'] or $option['value'] }}</option>
                @endif
            @endforeach
        @endif
    </select>
@endif
