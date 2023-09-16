<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\password\ResetPasswordRequest;
use App\Http\Requests\password\UpdatePasswordRequest;
use App\Mail\ResetPasswordEmail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

use function Laravel\Prompts\password;

class PasswordController extends Controller
{
    public function sendPasswordResetLink(ResetPasswordRequest $request)
    {
        try {
            $token = Str::random(40);
            $domaine = env('VITE_VUE_APP_URL');
            $url = $domaine . 'profile/reset-password/?token=' . $token;
            $mailData = [
                'title' => 'Reset Password',
                'body' => 'Please Click on the link to Reset Your Password.',
                'url' => $url
            ];

            Mail::to($request->email)->send(new ResetPasswordEmail($mailData));
            PasswordReset::updateOrCreate(['email' => $request->email], [
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            return response()->json('Reset Password Link  Email Sent Successfully', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PasswordController.sendPasswordResetLink',
                'error' => $e->getMessage()
            ], 400);
        }
    }
    public function resetPassword(UpdatePasswordRequest $request)
    {
        try {
            $user = User::find(Auth::user()->id);
            $user->password = Hash::make($request->password);
            $user->save();
            PasswordReset::where('email', $user->email)->delete();
            return response()->json('Password Updated Successfully', 200);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Something went wrong in PasswordController.resetPassword',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
