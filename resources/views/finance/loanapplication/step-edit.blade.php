@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('update_loan') }}</h5>
        </div>
    </div>

    <div class="card p-3 mb-2">
        <x-form.inputs-loop :fields="$fields" />

        {{-- Submit Button --}}
        <div class="mt-3">
            @if ($next_step_allow)
                <a href="{{ $next_step_route }}" class="btn btn-primary">Next</a>
            @endif
            <a href="{{ $back_step_route }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
