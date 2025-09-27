<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }
    public function showLoginForm()
    {
        return view('auth.pages.index');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if ($this->authService->login($credentials, $request->filled('remember'))) {
            return redirect()->intended(route('welcome'));
        }
        return back()->withErrors(['email' => 'Invalid credentials'])->withInput();
    }

    public function showRegisterForm()
    {
        return view('auth.pages.register');
    }

    public function register(RegisterRequest $request)
    {
        $this->authService->register($request->only('name', 'email', 'password', 'mobile', 'terms_accepted'));
        return redirect()->route('login');
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        return redirect()->route('login');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.pages.forgot_password');
    }
}
