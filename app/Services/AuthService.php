<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Validation\ValidationException;

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
        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'User not found.',
            ]);
        }
        if (!Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Invalid email.',
                'password' => 'Invalid password.',
            ]);
        }
        if (!$user->isActive()) {
            throw ValidationException::withMessages([
                'email' => 'Your account is inactive or banned.',
            ]);
        }
        
        Auth::login($user, $remember);
        Session::regenerate();
        return true;
    }

    public function register(array $data): User
    {
        $data['status'] = 'inactive';
        return $this->userRepository->create($data);
    }

    public function logout(): void
    {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
    }
}
