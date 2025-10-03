<div class="col-12">
    <table class="table table-bordered repeatable-table" data-name="{{ $field['name'] }}">
        <thead>
            <tr>
                @foreach ($field['fields'] as $subField)
                    <th>{{ $subField['label'] }}</th>
                @endforeach
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @php $rows = $field['value'] ?? [[]]; @endphp
            @foreach ($rows as $i => $row)
                <tr>
                    @foreach ($field['fields'] as $subField)
                        <td>
                            <input type="{{ $subField['type'] ?? 'text' }}"
                                name="{{ $field['name'] }}[{{ $i }}][{{ $subField['name'] }}]"
                                class="form-control" placeholder="{{ $subField['placeholder'] ?? '' }}"
                                value="{{ $row[$subField['name']] ?? '' }}">
                        </td>
                    @endforeach
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row">X</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end mt-2">
        <button type="button" class="btn btn-sm btn-primary add-repeatable-row" data-name="{{ $field['name'] }}">
            + Add {{ $field['label'] ?? 'Row' }}
        </button>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener("click", function(e) {
                // Remove row
                if (e.target.classList.contains("remove-row")) {
                    e.target.closest("tr").remove();
                }

                // Add new row
                if (e.target.classList.contains("add-repeatable-row")) {
                    let table = document.querySelector(
                        `.repeatable-table[data-name="${e.target.dataset.name}"] tbody`
                    );
                    let rowCount = table.rows.length;

                    // Template row banate hain based on first row
                    let firstRow = table.querySelector("tr");
                    let newRow = firstRow.cloneNode(true);

                    // Clear input values and update indexes
                    newRow.querySelectorAll("input").forEach((input) => {
                        let name = input.getAttribute("name");
                        let newName = name.replace(/\[\d+\]/, `[${rowCount}]`);
                        input.setAttribute("name", newName);
                        input.value = "";
                    });

                    table.appendChild(newRow);
                }
            });
        </script>
    @endpush
@endonce
