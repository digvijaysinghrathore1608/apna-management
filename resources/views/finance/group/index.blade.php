@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('groups') }}</h5>
            <div>
                <a href="{{ route('microfinance.groups.create') }}" class="btn btn-primary btn-sm">Add New</a>
                <button class="btn btn-success btn-sm">Export</button>
                <button class="btn btn-info btn-sm">Import</button>
            </div>
        </div>
    </div>


    <x-tables.datatable id="groups_management" title="groups_management" :columns="[
        ['data' => 'id', 'title' => '#', 'searchable' => false],
        ['data' => 'group_id', 'title' => 'Group Id', 'searchable' => true],
        ['data' => 'name', 'title' => 'Name', 'searchable' => false],
        ['data' => 'area', 'title' => 'Area', 'searchable' => true],
        ['data' => 'actions', 'title' => 'Actions', 'searchable' => false, 'orderable' => false],
    ]" :ajax="route('microfinance.groups.index')" />
@endsection
