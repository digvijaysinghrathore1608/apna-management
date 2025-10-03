@props(['fields'])

<div class="row">

    @foreach ($fields as $field)
        @if (($field['type'] ?? '') === 'hr')
            <div class="col-12">
                <hr>
            </div>
        @elseif (($field['type'] ?? '') === 'section')
            <div class="col-12">
                <div class="col-12 d-flex align-items-center justify-content-between my-5">
                    <h5 class="mb-0">{{ translate($field['label']) }}</h5>

                    {{-- Agar section address type hai to copy option dikhana --}}
                    @if (str_contains(strtolower($field['label']), 'address') && $field['label'] !== 'Current Address')
                        <button type="button" class="btn btn-sm btn-outline-primary copy-address" data-source="current"
                            data-target="{{ $field['name_prefix'] ?? Str::slug($field['label'], '_') }}">
                            Same as Current Address
                        </button>
                    @endif
                </div>
                <hr>
            </div>
        @elseif (($field['type'] ?? '') === 'break')
            <div class="col-12 mb-3"></div>
            <!-- ---->
        @elseif (($field['type'] ?? '') === 'repeatable')
            <x-form.repeatable :field="$field" />
        @else
            <div class="col-md-{{ $field['col'] ?? 6 }}">
                <x-form.input :name="$field['name']" :label="$field['label']" :type="$field['type'] ?? 'text'" :placeholder="$field['placeholder'] ?? ''" :value="$field['value'] ?? ''"
                    :required="$field['required'] ?? false ? 'true' : ''" :options="$field['options'] ?? []" :disabled="$field['disabled'] ?? false" :readonly="$field['readonly'] ?? false" />
            </div>
        @endif
    @endforeach
</div>

@once
    @push('scripts')
        <script>
            $(document).on('click', '.copy-address', function() {
                let source = $(this).data('source'); // "current"
                let target = $(this).data('target'); // "in_law_address" ya "mothers_address"

                // Mapping of fields
                let fields = ['line_1', 'line_2', 'pincode', 'city', 'state'];
                fields.forEach(function(field) {
                    let sourceVal = $(`[name="c_a_${field}"]`).val(); // current address value
                    $(`[name="${target}_a_${field}"]`).val(sourceVal); // target address set
                });
            });
        </script>
    @endpush
@endonce
