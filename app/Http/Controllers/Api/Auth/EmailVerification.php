<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class EmailVerification extends Controller
{
//    public function verificationEmail(Request $request)
//    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return [
//                'message' => 'Alredy verified'
//            ];
//        }
//        $request->user()->sendEmailVerificationNotification();
//        return ['status' => 'send verified link'];
//    }
//
//    public function verify(EmailVerificationRequest $request)
//    {
//        if ($request->user()->hasVerifiedEmail()) {
//            return [
//                'message' => 'Alredy verified'
//            ];
//        }
//        if ($request->user()->markEmailAsVerified()) {
//            event(new Verified($request->user()));
//        }
//        return [
//            'message' => 'emil has verified'
//        ];
//    }
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return redirect()->to('/');
    }

    public function resend()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return response()->json(["msg" => "Email already verified."], 400);
        }

        auth()->user()->sendEmailVerificationNotification();

        return response()->json(["msg" => "Email verification link sent on your email id"]);
    }
}
