<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class PasswordResetController extends Controller
{
    public function showResetForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'No user found with this email address.');
        }

        // Generate reset token
        $token = Str::random(60);

        // Store token in database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        // Create reset link
        $resetUrl = route('password.reset.form', [
            'token' => $token,
            'email' => $request->email,
        ]);

        // Send email via EmailJS
        try {
            $this->sendEmailViaEmailJS($user, $resetUrl);
            return back()->with('status', 'Password reset link has been sent to your email. Please check your inbox.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset email: ' . $e->getMessage());
        }
    }

    private function sendEmailViaEmailJS($user, $resetUrl)
    {
        $response = Http::post('https://api.emailjs.com/api/v1.0/email/send', [
            'service_id' => env('EMAILJS_SERVICE_ID'),
            'template_id' => env('EMAILJS_TEMPLATE_ID'),
            'user_id' => env('EMAILJS_PUBLIC_KEY'),
            'template_params' => [
                'email' => $user->email,
                'link' => $resetUrl,
            ],
        ]);

        if (!$response->successful()) {
            throw new \Exception('EmailJS API error: ' . $response->body());
        }

        return $response;
    }

    public function showResetPasswordForm($token)
    {
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', request('email'))
            ->first();

        if (!$tokenRecord || !Hash::check($token, $tokenRecord->token)) {
            return redirect('/login')->with('error', 'Invalid or expired reset link.');
        }

        return view('auth.passwords.reset', ['token' => $token, 'email' => request('email')]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required',
        ]);

        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
            return back()->with('error', 'Invalid or expired reset link.');
        }

        // Check if token is not older than 1 hour
        if (now()->diffInMinutes($tokenRecord->created_at) > 60) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->with('error', 'Reset link has expired. Please request a new one.');
        }

        // Update user password
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Delete token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Your password has been reset successfully. Please login with your new password.');
    }
}
