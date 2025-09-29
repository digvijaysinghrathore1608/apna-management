@extends('partials.layouts.app')

@section('content')
    <div class="card p-3 mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">{{ translate('edit_schema') }} -- {{ $schema->name }}</h5>
        </div>
    </div>

    <div class="card p-3 mb-2">
        <form action="{{ route('microfinance.schema.update', $schema->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="row">
                @foreach ($fields as $field)
                    <div class="col-md-{{ $field['col'] ?? 6 }}">
                        <x-form.input :name="$field['name']" :label="$field['label']" :type="$field['type'] ?? 'text'" :placeholder="$field['placeholder'] ?? ''"
                            :disabled="$field['disabled'] ?? false ? 'true' : ''" :required="$field['required'] ?? false ? 'true' : ''" :options="$field['options'] ?? []" :value="$field['value']" />
                    </div>
                @endforeach
            </div>

            {{-- Submit Button --}}
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('microfinance.schema.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
