@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('create_customer') }}</h5>
        </div>
    </div>

    <div class="card p-3 mb-2">
        <form action="{{ route('microfinance.customers.store') }}" method="POST">
            @csrf
            <x-form.inputs-loop :fields="$fields" />
            {{-- Submit Button --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('microfinance.customers.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
