@extends('auth.partials.layouts.app')

@section('content')
    <!-- Register -->
    <div class="card px-sm-6 px-0">
        <div class="card-body">
            <!-- Logo -->
            @include('partials.logo._main')
            <!-- /Logo -->
            <h4 class="mb-1">Adventure starts here 🚀</h4>
            <p class="mb-6">Make your app management easy and fun!</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('register.attempt') }}" method="POST">
                @csrf
                <x-form.input name="name" label="name" type="text" placeholder="john doe" :required="true" />
                <x-form.input name="email" label="email" type="email" placeholder="john.doe@example.com"
                    :required="true" />
                <x-form.input name="mobile" label="mobile" type="text" placeholder="123-456-7890" :required="true" />
                <x-form.password name="password" label="password" type="password" placeholder="••••••••••••"
                    :required="true" />
                <x-form.password name="password_confirmation" label="password confirmation" type="password"
                    placeholder="••••••••••••" :required="true" />

                <div class="my-7">
                    <x-form.checkbox name="terms_accepted" id="terms-me">
                        {{ ucwords('I agree to') }}
                        <a href="javascript:void(0);">{{ ucwords('privacy policy & terms') }}</a>
                    </x-form.checkbox>
                </div>
                <button class="btn btn-primary d-grid w-100">Sign up</button>
            </form>

            <p class="text-center">
                <span>Already have an account?</span>
                <a href="{{ route('login') }}">
                    <span>Sign in instead</span>
                </a>
            </p>
        </div>
    </div>
    <!-- /Register -->
@endsection
