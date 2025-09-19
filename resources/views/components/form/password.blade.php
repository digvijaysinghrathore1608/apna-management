@props([
    'name' => 'password',
    'label' => 'Password',
    'placeholder' => '············',
    'id' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $containerClass = 'mb-6 form-control-validation fv-plugins-icon-container' . ($hasError ? ' fv-plugins-bootstrap5-row-invalid' : '');
@endphp

<div class="{{ $containerClass }}">
    <div class="form-password-toggle">
        <label class="form-label" for="{{ $id }}">{{ $label }}</label>
        <div class="input-group input-group-merge has-validation">
            <input
                class="form-control{{ $hasError ? ' is-invalid' : '' }}"
                type="password"
                id="{{ $id }}"
                name="{{ $name }}"
                placeholder="{{ $placeholder }}"
                aria-describedby="multicol-password2"
            >
            <span class="input-group-text cursor-pointer" id="multicol-password2">
                <i class="icon-base bx bx-hide"></i>
            </span>
        </div>
        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
            @error($name)
                <div data-field="{{ $id }}" data-validator="notEmpty">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>