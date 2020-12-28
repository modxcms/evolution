<input class="form-control {{ $class ?? '' }}" type="{{ $type ?? 'text' }}"
    @if(!empty($id)) id="{{ $id }}" @elseif(!empty($name)) id="{{ $name }}" @endif
@if(!empty($name)) name="{{ $name }}" @endif
@if(isset($value)) value="{{ $value }}" @endif
@if(isset($placeholder)) placeholder="{{ $placeholder }}" @endif
@if(!empty($checked)) checked @endif
@if($disabled == 1) disabled @endif
    {!! $attributes ?? '' !!}
/>
@if($disabled == 1)
    <input type="hidden" name="{{ $name }}" value="1">
    @endif