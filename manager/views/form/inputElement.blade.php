<input class="form-control {{ $class or '' }}" type="{{ $type or 'text' }}" id="{{ $id or $name }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}"
        {!! $attributes or '' !!}
        @if($readonly) readonly @endif
/>
