<textarea class="form-control {{ $class or '' }}" name="{{ $name }}" id="{{ $id or $name }}" rows="{{ $rows or '3' }}"
    @if(!empty($placeholder)) placeholder="{{ $placeholder }}" @endif
{!! $attributes or '' !!}
@if(!empty($readonly)) readonly @endif
>{{ $value }}</textarea>
