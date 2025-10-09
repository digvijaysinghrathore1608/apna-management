@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('edit_branch') }} -- {{ $branch->name }}</h5>
        </div>
    </div>

    <div class="card p-3 mb-2">
        <form action="{{ route('microfinance.branch.update', $branch->id) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <x-form.inputs-loop :fields="$fields" />

            {{-- Submit Button --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('microfinance.branch.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
