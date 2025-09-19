<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(array $credentials, bool $remember = false): bool
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $remember);
            Session::regenerate();
            return true;
        }
        return false;
    }

    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function logout(): void
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    }
}
