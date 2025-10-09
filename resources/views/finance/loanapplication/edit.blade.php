@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('update_loan') }}</h5>
        </div>
    </div>

    <div class="card p-3 mb-2">
        <form action="{{ $update_route }}" method="POST">
            @csrf
            @method('PATCH')
            <x-form.inputs-loop :fields="$fields" />

            {{-- Submit Button --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('microfinance.loanapplication.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
