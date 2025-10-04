<div class="col-12 repeatable-grid" data-name="{{ $field['name'] }}">
    @php $rows = $field['value'] ?? [[]]; @endphp

    @foreach ($rows as $row)
        <div class="repeatable-row border rounded p-3 mb-4 shadow-sm" id="row-{{ $row['id'] ?? 'new' }}"
            data-id="{{ $row['id'] ?? '' }}">

            {{-- 🔹 Save / Update FORM --}}
            <form
                action="{{ isset($row['id'])
                    ? route($field['actions']['update']['route'], $row['id'])
                    : route($field['actions']['store']['route']) }}"
                method="POST" class="family-form w-100">
                @csrf
                @if (isset($row['id']))
                    @method('PATCH')
                @endif

                <div class="row g-3">
                    @foreach ($field['fields'] as $subField)
                        <div class="col-md-{{ $subField['col'] ?? 3 }}">
                            <x-form.input type="{{ $subField['type'] ?? 'text' }}" name="{{ $subField['name'] }}"
                                label="{{ $subField['label'] }}" class="form-control"
                                placeholder="{{ $subField['placeholder'] ?? '' }}"
                                value="{{ $row[$subField['name']] ?? '' }}" :options="$subField['options'] ?? []" :required="$subField['required'] ?? false" />
                        </div>
                    @endforeach
                </div>

                {{-- 🔹 Buttons --}}
                <div class="d-flex justify-content-end align-items-center gap-2 mt-3">
                    <button type="submit" class="btn btn-success btn-sm px-3">
                        {{ isset($row['id']) ? 'Update' : 'Save' }}
                    </button>

                    @if (isset($row['id']))
                        <button type="button" class="btn btn-danger btn-sm px-3 delete-btn"
                            data-url="{{ route($field['actions']['delete']['route'], $row['id']) }}">
                            Delete
                        </button>
                    @endif
                </div>
            </form>
        </div>

        @if (!$loop->last)
            <hr>
        @endif
    @endforeach

    {{-- Global delete form (placed once, at bottom of page) --}}
    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>
</div>


@once
    @push('scripts')
        <script>
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn')) {
                    e.preventDefault();

                    if (!confirm('Are you sure you want to delete this record?')) return;

                    const url = e.target.dataset.url;
                    const form = document.getElementById('deleteForm');
                    form.action = url;
                    form.submit(); // ✅ triggers normal redirect flow
                }
            });
        </script>
    @endpush

@endonce
