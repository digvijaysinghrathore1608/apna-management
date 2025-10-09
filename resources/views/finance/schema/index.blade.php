@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('schema') }}</h5>
            <div>
                <a href="{{ route('microfinance.schema.create') }}" class="btn btn-primary btn-sm">Add New</a>
                <button class="btn btn-success btn-sm">Export</button>
                <button class="btn btn-info btn-sm">Import</button>
            </div>
        </div>
    </div>


    <x-tables.datatable id="schema_management" title="schema_management" :columns="[
        ['data' => 'id', 'title' => '#', 'searchable' => false],
        ['data' => 'name', 'title' => 'Name', 'searchable' => true],
        ['data' => 'amount', 'title' => 'Amount', 'searchable' => true],
        ['data' => 'interest_rate', 'title' => 'Interest Rate', 'searchable' => true],
        ['data' => 'insurance_amount', 'title' => 'insurance_amount', 'searchable' => true],
        ['data' => 'duration_type', 'title' => 'duration_type', 'searchable' => true],
        ['data' => 'duration', 'title' => 'duration', 'searchable' => true],
        ['data' => 'late_fee', 'title' => 'late_fee', 'searchable' => true],
        ['data' => 'late_fee_apply', 'title' => 'late_fee_apply', 'searchable' => true],
        ['data' => 'emi_amount', 'title' => 'emi_amount', 'searchable' => true],
        ['data' => 'actions', 'title' => 'Actions', 'searchable' => false, 'orderable' => false],
    ]" :ajax="route('microfinance.schema.index')" />
@endsection
