<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('products.index');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        // Generalized error message across all failures
        $generalizedError = 'Email atau password salah.';

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors(['auth_error' => $generalizedError])
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (! Auth::attempt($credentials, $remember)) {
            return back()
                ->withErrors(['auth_error' => $generalizedError])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        return redirect()->intended(route('products.index'))
            ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
    }

    /**
     * Show the registration form.
     */
    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('products.index');
        }

        return view('auth.register');
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request): RedirectResponse
    {
        $generalizedError = 'Email atau password salah.';

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        // If basic format fails or passwords do not match
        if ($validator->fails()) {
            return back()
                ->withErrors(['auth_error' => $generalizedError])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        // If email already exists, generalize error to prevent account enumeration
        $existing = User::where('email', $request->email)->first();
        if ($existing) {
            return back()
                ->withErrors(['auth_error' => $generalizedError])
                ->withInput($request->except('password', 'password_confirmation'));
        }

        $user = User::create([
            'name' => trim($request->name),
            'email' => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'phone' => $request->phone ? trim($request->phone) : null,
            'address' => $request->address ? trim($request->address) : null,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('products.index')
            ->with('success', 'Pendaftaran berhasil. Selamat berbelanja di TokoKita!');
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('products.index')
            ->with('info', 'Anda telah berhasil logout.');
    }
}
