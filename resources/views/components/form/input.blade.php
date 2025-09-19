@props([
    'name',
    'label',
    'type' => 'text',
    'placeholder' => '',
    'id' => null,
    'value' => '',
    'autofocus' => false,
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $containerClass = 'mb-6 form-control-validation fv-plugins-icon-container' . ($hasError ? ' fv-plugins-bootstrap5-row-invalid' : '');
@endphp

<div class="{{ $containerClass }}">
    <label class="form-label" for="{{ $id }}">{{ $label }}</label>
    <input
        class="form-control{{ $hasError ? ' is-invalid' : '' }}"
        type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        placeholder="{{ $placeholder }}"
        value="{{ old($name, $value) }}"
        @if($autofocus) autofocus @endif
    >
    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
        @error($name)
            <div data-field="{{ $id }}" data-validator="notEmpty">{{ $message }}</div>
        @enderror
    </div>
</div>
