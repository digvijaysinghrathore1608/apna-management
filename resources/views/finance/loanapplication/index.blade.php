@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('loan_applications') }}</h5>
            <div>
                <button class="btn btn-success btn-sm">Export</button>
                <button class="btn btn-info btn-sm">Import</button>
            </div>
        </div>
    </div>


    <x-tables.datatable id="loan_application_management" title="loan_application_management" :columns="[
        ['data' => 'id', 'title' => '#', 'searchable' => false],
        ['data' => 'customer_id', 'title' => 'Customer Id'],
        ['data' => 'loan_id', 'title' => 'Loan Id'],
        ['data' => 'status', 'title' => 'Status'],
        ['data' => 'actions', 'title' => 'Actions', 'searchable' => false, 'orderable' => false],
    ]"
        :ajax="route('microfinance.loanapplication.index')" />
@endsection
