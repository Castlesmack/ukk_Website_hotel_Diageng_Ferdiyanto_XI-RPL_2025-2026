<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function show()
    {
        return view('auth.register');
    }

    /**
     * Handle user registration.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        // Validate registration data
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/'
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9\-\+\s]+$/'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'confirm' => 'accepted',
        ], [
            'name.required' => 'Full name is required.',
            'name.string' => 'Name must be text only.',
            'name.max' => 'Name cannot exceed 255 characters.',
            'name.regex' => 'Name can only contain letters and spaces.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered. Please log in or use a different email.',
            'email.regex' => 'Email format is invalid.',
            'phone.required' => 'Phone number is required.',
            'phone.string' => 'Phone must be text.',
            'phone.max' => 'Phone cannot exceed 20 characters.',
            'phone.regex' => 'Phone can only contain numbers, spaces, hyphens, and plus signs.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be text.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'confirm.accepted' => 'You must confirm that all details are correct.',
        ]);

        try {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($validated['password']),
                'role' => 'user',
            ]);

            // Automatically log in the new user
            Auth::login($user);

            return redirect('/')->with('success', 'Registration successful! Welcome to Ade Villa.');
        } catch (\Exception $e) {
            return back()
                ->withInput($request->only('name', 'email', 'phone'))
                ->withErrors(['error' => 'Registration failed. Please try again later.']);
        }
    }
}
