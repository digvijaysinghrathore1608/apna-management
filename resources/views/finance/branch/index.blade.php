@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('branch') }}</h5>
            <div>
                <a href="{{ route('microfinance.branch.create') }}" class="btn btn-primary btn-sm">Add New</a>
                <button class="btn btn-success btn-sm">Export</button>
                <button class="btn btn-info btn-sm">Import</button>
            </div>
        </div>
    </div>


    <x-tables.datatable id="branch_management" title="branch_management" :columns="[
        ['data' => 'id', 'title' => '#', 'searchable' => false],
        ['data' => 'code', 'title' => 'Branch Code'],
        ['data' => 'name', 'title' => 'Name'],
        ['data' => 'email', 'title' => 'Email'],
        ['data' => 'mobile', 'title' => 'Mobile'],
        ['data' => 'address', 'title' => 'Address'],
        ['data' => 'actions', 'title' => 'Actions', 'searchable' => false, 'orderable' => false],
    ]" :ajax="route('microfinance.branch.index')" />
@endsection
