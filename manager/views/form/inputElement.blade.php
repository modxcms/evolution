<input class="form-control {{ $class ?? '' }}" type="{{ $type ?? 'text' }}"
    @if(!empty($id)) id="{{ $id }}" @elseif(!empty($name)) id="{{ $name }}" @endif
@if(!empty($name)) name="{{ $name }}" @endif
@if(isset($value)) value="{{ $value }}" @endif
@if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
@if(!empty($checked)) checked @endif
@if(!empty($readonly)) readonly @endif
@if(!empty($disabled)) disabled @endif
    {!! $attributes ?? '' !!}
/>
