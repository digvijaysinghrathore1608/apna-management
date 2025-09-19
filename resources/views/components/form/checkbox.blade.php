@props([
    'name',
    'label',
    'id' => null,
    'checked' => false,
    'value' => 1,
    'class' => '',
])

@php
    $id = $id ?? $name;
@endphp

<div class="form-check mb-0">
    <input class="form-check-input {{ $class }}" type="checkbox" id="{{ $id }}" name="{{ $name }}" value="{{ $value }}" {{ old($name, $checked) ? 'checked' : '' }} />
    <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
</div>
