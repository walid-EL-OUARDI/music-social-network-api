<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\VerificationEmail;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendVerifyEmail(String $email)
    {
        try {
            $hashedEmail = sha1($email);
            $domaine = env('VITE_VUE_APP_URL');
            $url = $domaine . 'profile/verify-email/' . Auth::user()->id . '/' . $hashedEmail;
            // $email = $user->email;
            $mailData = [
                'title' => 'Email Verification',
                'body' => 'Please Click on the link to confirm your email address and activate your account.',
                'url' => $url
            ];

            Mail::to($email)->send(new VerificationEmail($mailData));

            return response()->json('verification Email Sent Successfully', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in EmailController.sendVerifyEmail',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function verifyEmail($id, $hash)
    {
        try {
            $user = User::find($id);

            if (!$user || !hash_equals($hash, sha1($user->getEmailForVerification()))) {
                return response()->json(['message' => 'Invalid verification link'], 400);
            }
            $user->markEmailAsVerified();
            $user->email_verified = 1;
            $user->save();

            return response()->json(['message' => 'Email verified successfully'], 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in EmailController.sendVerifyEmail',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
