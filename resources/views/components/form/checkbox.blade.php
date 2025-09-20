@props(['name', 'id' => null, 'checked' => false, 'value' => 1, 'class' => ''])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $containerClass =
        'mb-6 form-control-validation fv-plugins-icon-container' .
        ($hasError ? ' fv-plugins-bootstrap5-row-invalid' : '');
@endphp

<div class="{{ $containerClass }}">
    <div class="form-check">
        <input type="checkbox" class="form-check-input {{ $class }} {{ $hasError ? 'is-invalid' : '' }}"
            id="{{ $id }}" name="{{ $name }}" value="{{ $value }}"
            {{ old($name, $checked) ? 'checked' : '' }}>
        <label class="form-check-label" for="{{ $id }}">{{ $slot }}</label>
        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
            @error($name)
                <div data-field="{{ $id }}" data-validator="notEmpty">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
