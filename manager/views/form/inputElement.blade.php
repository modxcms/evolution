<input class="form-control {{ $class or '' }}" type="{{ $type or 'text' }}"
    @if(!empty($id)) id="{{ $id }}" @elseif(!empty($name)) id="{{ $name }}" @endif
@if(!empty($name)) name="{{ $name }}" @endif
@if(isset($value)) value="{{ $value }}" @endif
@if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
{!! $attributes or '' !!}
@if(!empty($readonly)) readonly @endif
/>
