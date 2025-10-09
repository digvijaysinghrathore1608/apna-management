@props(['id', 'ajax' => null, 'columns' => [], 'options' => '{}'])

@once
    <!-- ✅ DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- ✅ DataTables JS -->
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    @endpush
@endonce

<div class="card p-3">
    <div class="table-responsive text-nowrap">
        <table id="{{ $id }}" class="table table-hover table-bordered">
            <thead>
                <tr>
                    @foreach ($columns as $col)
                        <th>{{ $col['title'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        $(function() {
            let options = {
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ $ajax }}",
                columns: @json($columns),
                language: {
                    searchPlaceholder: "Search...",
                    search: "",
                },
            }

            let extra = {!! $options !!};
            options = Object.assign(options, extra);

            $('#{{ $id }}').DataTable(options);
        });
    </script>
@endpush
