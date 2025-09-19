@extends('auth.partials.layouts.app')

@section('content')
    <!-- Forgot Password -->
    <div class="card px-sm-6 px-0">
        <div class="card-body">
            <!-- Logo -->
            @include('partials.logo._main')
            <!-- /Logo -->
            <h4 class="mb-1">Forgot Password? 🔒</h4>
            <p class="mb-6">Enter your email and we'll send you instructions to reset your password</p>
            <form id="formAuthentication" class="mb-6" action="{{route('dashboard')}}">
                <div class="mb-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email"
                        autofocus />
                </div>
                <button class="btn btn-primary d-grid w-100">Send Reset Link</button>
            </form>
            <div class="text-center">
                <a href="{{route('login')}}" class="d-flex justify-content-center">
                    <i class="icon-base bx bx-chevron-left me-1"></i>
                    Back to login
                </a>
            </div>
        </div>
    </div>
    <!-- /Forgot Password -->
@endsection
