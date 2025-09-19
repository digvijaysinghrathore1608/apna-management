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

            <form id="formAuthentication" class="mb-6" action="{{route('dashboard')}}">
                <div class="mb-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username"
                        placeholder="Enter your username" autofocus />
                </div>
                <div class="mb-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email"
                        placeholder="Enter your email" />
                </div>
                <div class="form-password-toggle">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                    </div>
                </div>
                <div class="my-7">
                    <div class="form-check mb-0">
                        <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                        <label class="form-check-label" for="terms-conditions">
                            I agree to
                            <a href="javascript:void(0);">privacy policy & terms</a>
                        </label>
                    </div>
                </div>
                <button class="btn btn-primary d-grid w-100">Sign up</button>
            </form>

            <p class="text-center">
                <span>Already have an account?</span>
                <a href="{{route('login')}}">
                    <span>Sign in instead</span>
                </a>
            </p>
        </div>
    </div>
    <!-- /Register -->
@endsection
