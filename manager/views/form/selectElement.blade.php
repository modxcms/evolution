@if(!empty($options))
    <select class="form-control {{ $class or '' }}" name="{{ $name }}" id="{{ $id or $name }}"
            {!! $attributes or '' !!}
    >
        @foreach ($options as $key => $option)
            @if(isset($option['optgroup']) && !empty($option['optgroup']['options']))
                <optgroup label="{{ $option['optgroup']['name'] or 'optgroup' }}">
                    @foreach($option['optgroup']['options'] as $opt)
                        <option value="{{ $opt['value'] }}" @if(isset($value) && $value == $opt['value'])selected="selected"@endif>{{ $opt['text'] }}</option>
                    @endforeach
                </optgroup>
            @elseif(is_string($option))
                @if($as == 'keys')
                    <option value="{{ $key }}"
                            @if($value == $key)
                            selected="selected"
                            @endif
                    >{{ $key }}</option>
                @elseif($as == 'values')
                    <option value="{{ $option }}"
                            @if($value == $option)
                            selected="selected"
                            @endif
                    >{{ ucwords(str_replace("_", " ", $option)) }}</option>
                @else
                    <option value="{{ $key }}"
                            @if($value == $key)
                            selected="selected"
                            @endif
                    >{{ ucwords(str_replace("_", " ", $option)) }}</option>
                @endif
            @else
                <option value="{{ $option['value'] or ''}}"
                        @if(isset($value) && $value == $option['value'])
                        selected="selected"
                        @endif
                >{{ $option['text'] or $option['value'] }}</option>
            @endif
        @endforeach
    </select>
@endif
