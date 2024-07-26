<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthServiceInterface;
use Illuminate\Support\Facades\Redirect;

class AuthController extends Controller
{
    //
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $this->authService->register($data);

        return Redirect::route('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->authService->login($credentials)) {
            return Redirect::intended('dashboard');
        }

        return Redirect::back()->withErrors(['email' => 'Invalid credentials']);
    }
}
