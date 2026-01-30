<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Handle user login.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'email' => [
                'required',
                'email',
                'regex:/^[a-zA-Z0-9._\-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'
            ],
            'password' => [
                'required',
                'string',
                'min:6'
            ],
            'confirm' => 'required',
        ], [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.regex' => 'Email format is invalid.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a text string.',
            'password.min' => 'Password must be at least 6 characters.',
            'confirm.required' => 'You must confirm that all details are correct.',
        ]);

        // Attempt authentication
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password']
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            $user = Auth::user();
            
            // Redirect based on user role
            return $this->redirectByRole($user);
        }

        // Authentication failed - throw validation exception
        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records. Please try again.',
        ]);
    }

    /**
     * Redirect user based on their role.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    private function redirectByRole($user)
    {
        return match($user->role) {
            'admin' => redirect('/admin/dashboard')->with('success', 'Welcome back, Admin!'),
            'receptionist' => redirect('/reception/dashboard')->with('success', 'Welcome back, Receptionist!'),
            default => redirect('/')->with('success', 'Login successful! Welcome to Ade Villa.'),
        };
    }

    /**
     * Handle user logout.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/')->with('success', 'You have been logged out successfully.');
    }
}
