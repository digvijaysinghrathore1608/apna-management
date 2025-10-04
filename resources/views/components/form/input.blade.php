@props([
    'name',
    'label' => '',
    'type' => 'text', // text, email, number, textarea, select, password, date...
    'placeholder' => '',
    'id' => null,
    'value' => '',
    'autofocus' => false,
    'required' => false,
    'readonly' => false,
    'disabled' => false,
    'class' => '',
    'help' => '',
    'rows' => 3, // for textarea
    'options' => [], // for select
    'max' => null,
])

@php
    $id = $id ?? $name;
    $hasError = $errors->has($name);
    $containerClass =
        'mb-3 form-control-validation fv-plugins-icon-container' .
        ($hasError ? ' fv-plugins-bootstrap5-row-invalid' : '');
@endphp

<div class="{{ $containerClass }}">
    <label class="form-label" for="{{ $id }}">
        {{ translate($label) }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>

    {{-- Handle input type --}}
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}"
            placeholder="{{ translate($placeholder) }}"
            class="form-control {{ $class }}{{ $hasError ? ' is-invalid' : '' }}" {{ $required ? 'required' : '' }}
            {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
            @if ($autofocus) autofocus @endif>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}"
            class="form-select select2 {{ $class }}{{ $hasError ? ' is-invalid' : '' }}"
            {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}>
            <option value="">-- Select {{ $label }} --</option>
            @foreach ($options as $optValue => $optLabel)
                <option value="{{ $optValue }}" @selected(old($name, $value) == $optValue)>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
        {{-- Load Select2 only once --}}
        @once
            @push('styles')
                <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
                <link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@1.5.2/dist/select2-bootstrap4.min.css"
                    rel="stylesheet">
            @endpush

            @push('scripts')
                <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        $('.select2').select2({
                            theme: 'bootstrap4', // Bootstrap look
                            width: '100%',
                            allowClear: true
                        });
                    });
                </script>
            @endpush
        @endonce
    @elseif($type === 'file' || $type === 'image')
        <div class="file-upload-wrapper">
            <input type="file" id="{{ $id }}" name="{{ $name }}"
                accept="{{ $type === 'image' ? 'image/*' : '*' }}"
                class="form-control {{ $class }}{{ $hasError ? ' is-invalid' : '' }}"
                {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
                @if ($autofocus) autofocus @endif>

            {{-- ✅ Show existing image preview if available --}}
            @if ($type === 'image' && !empty($value))
                <div class="mt-2">
                    <img src="{{ $value }}" alt="Preview" class="img-thumbnail rounded"
                        style="max-width: 150px; max-height: 150px; object-fit: cover;">
                </div>
            @endif

            {{-- ✅ Preview selected file (live JS) --}}
            @if ($type === 'image')
                <div class="preview-container mt-2" id="preview-{{ $id }}"></div>
                @once
                    @push('scripts')
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                const input = document.getElementById('{{ $id }}');
                                const preview = document.getElementById('preview-{{ $id }}');
                                if (!input) return;

                                input.addEventListener('change', function(e) {
                                    const file = e.target.files[0];
                                    if (file) {
                                        const reader = new FileReader();
                                        reader.onload = function(ev) {
                                            preview.innerHTML =
                                                `<img src="${ev.target.result}" class="img-thumbnail rounded" style="max-width:150px;max-height:150px;object-fit:cover;">`;
                                        };
                                        reader.readAsDataURL(file);
                                    } else {
                                        preview.innerHTML = '';
                                    }
                                });
                            });
                        </script>
                    @endpush
                @endonce
            @endif
        </div>
    @elseif($type === 'switch')
        <div class="form-check form-switch">
            <input type="hidden" name="{{ $name }}" value="0">
            <input type="checkbox" id="{{ $id }}" name="{{ $name }}" value="1"
                class="form-check-input {{ $class }}{{ $hasError ? ' is-invalid' : '' }}"
                {{ old($name, $value) ? 'checked' : '' }} {{ $required ? 'required' : '' }}
                {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}>
            <label class="form-check-label" for="{{ $id }}">
                {{ translate($label) }}
            </label>
        </div>
    @else
        <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}"
            placeholder="{{ translate($placeholder) }}" value="{{ old($name, $value) }}"
            class="form-control {{ $class }}{{ $hasError ? ' is-invalid' : '' }}"
            {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} {{ $disabled ? 'disabled' : '' }}
            @if ($autofocus) autofocus @endif
            @if ($max) max="{{ $max }}" @endif>
    @endif

    {{-- Help text --}}
    @if ($help)
        <small class="form-text text-muted">{{ $help }}</small>
    @endif

    {{-- Error message --}}
    <div class="fv-plugins-message-container invalid-feedback">
        @error($name)
            <div data-field="{{ $id }}" data-validator="notEmpty">{{ $message }}</div>
        @enderror
    </div>
</div>
