@extends('auth.partials.layouts.app')

@section('content')
    <!-- Register -->
    <div class="card px-sm-6 px-0">
        <div class="card-body">
            <!-- Logo -->
            @include('partials.logo._main')
            <!-- /Logo -->
            <h4 class="mb-1">Welcome to Sneat! 👋</h4>
            <p class="mb-6">Please sign-in to your account and start the adventure</p>

            <form id="formAuthentication" class="mb-6" action="{{ route('login.attempt') }}" method="POST">
                @csrf

                <x-form.input name="email" label="Email" type="email" placeholder="john.doe" :autofocus="true"
                    :required="true" />

                <x-form.password name="password" label="Password" placeholder="••••••••••••" :required="true" />

                <div class="mb-8">
                    <x-form.checkbox name="remember" id="remember-me">
                        Remember Me
                        <a href="{{ route('forgot.password') }}">
                            <span>Forgot Password?</span>
                        </a>
                    </x-form.checkbox>
                </div>
                <div class="mb-6">
                    <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                </div>
            </form>

            <p class="text-center">
                <span>New on our platform?</span>
                <a href="{{ route('register') }}">
                    <span>Create an account</span>
                </a>
            </p>
        </div>
    </div>
    <!-- /Register -->
@endsection
