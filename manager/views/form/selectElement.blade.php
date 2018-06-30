@if(!empty($options))
    <select class="form-control {{ $class or '' }}" name="{{ $name }}" id="{{ $id or $name }}"
            {!! $attributes or '' !!}
    >
        @foreach ($options as $option)
            @if(isset($option['optgroup']) && !empty($option['optgroup']['options']))
                <optgroup label="{{ $option['optgroup']['name'] or 'optgroup' }}">
                    @foreach($option['optgroup']['options'] as $opt)
                        <option value="{{ $opt['value'] }}" @if(isset($value) && $value == $opt['value'])selected="selected"@endif>{{ $opt['text'] }}</option>
                    @endforeach
                </optgroup>
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
