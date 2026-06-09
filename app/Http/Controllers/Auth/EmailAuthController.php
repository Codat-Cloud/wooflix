<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EmailAuthController extends Controller
{
    /**
     * Render the unified login/register view page.
     */
    public function showLoginPage()
    {
        if (Auth::check()) {
            return redirect()->url('/cart');
        }
        return view('auth.email-login');
    }

    /**
     * STAGE 1: Generate and send a secure 6-digit OTP to the user's email.
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $otp = rand(100000, 999999);
        
        // Save verification data to session (valid for 10 minutes)
        session([
            'auth_email'  => $request->email,
            'auth_otp'    => $otp,
            'auth_expiry' => now()->addMinutes(10)
        ]);

        try {

            $emailData = ['otp' => $otp, 'settings' => []];
            // Send email using a quick raw mail closure
            Mail::send('emails.auth-otp', $emailData, function ($message) use ($request) {
                            $message->to($request->email)
                                ->subject('🐾 Your Wooflix Verification Code');
                        });

            return response()->json(['success' => true, 'message' => 'Verification code sent to your email!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send email. Please check your configuration.'], 500);
        }
    }

    /**
     * STAGE 2: Verify the OTP and authenticate the user (Login or Register).
     */
    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required|numeric']);

        $cachedOtp    = session('auth_otp');
        $cachedEmail  = session('auth_email');
        $cachedExpiry = session('auth_expiry');

        if (!$cachedOtp || now()->gt($cachedExpiry) || (int)$request->otp !== (int)$cachedOtp) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired verification code.'], 422);
        }

        // Find or create the user cleanly
        $user = User::firstOrCreate(
            ['email' => $cachedEmail],
            [
                'name'     => explode('@', $cachedEmail)[0], // Fallback name from email prefix
                'role'     => 'customer',
                'password' => bcrypt(Str::random(24)) // Secure random password string
            ]
        );

        // Log the user in
        Auth::login($user, true);

        // Clear auth sessions
        session()->forget(['auth_email', 'auth_otp', 'auth_expiry']);

        return response()->json(['success' => true, 'redirect' => url('/checkout')]);
    }
}