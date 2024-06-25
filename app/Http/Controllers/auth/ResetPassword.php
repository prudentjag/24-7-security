<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use App\Http\Response;
use App\Models\User;
use App\Services\MailServices;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPassword extends Controller
{
    protected $responseService;
    protected $mailServices;

    public function __construct(ResponseService $responseService, MailServices $mailServices)
    {
        $this->responseService = $responseService;
        $this->mailServices = $mailServices;
    }

    public function ResetPassword(PasswordRequest $request) {
        $validated = $request->validated();

        $passwordReset = DB::table('password_reset_tokens')->where('token', $validated['token'])->first();

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return $this->responseService->error('User not found.', Response::HTTP_NOT_FOUND);
        }

        // Update the user's password
        $user->password = Hash::make($validated['password']);
        $user->save();

        // Optionally, delete the used token
        DB::table('password_reset_tokens')->where('token', $validated['token'])->delete();

        return $this->responseService->success($user, 'Password reset successfully.');
    }

    public function Generate_reset_Token(Request $request){
        $randomToken = Str::random(70);
        $token = generate_tokens(null, null,'password_reset_tokens', 'token', $randomToken);
        $oldtoken= DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if(!empty($oldtoken)){
            $token = $oldtoken->token;
        } else{
            $addtoken = DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now(),
            ]);
            if (!$addtoken) {
                return $this->responseService->error('Error inserting token', Response::HTTP_BAD_REQUEST);
            }
        }

        $mail = $this->mailServices->Mailer(['token' => $token, 'email' => $request->email], \App\Mail\PasswordReset::class);

        return $mail ? $this->responseService->success(null,'A password reset link has been sent to your mail') : $this->responseService->error('Error occured sending mail', Response::HTTP_BAD_REQUEST);
    }
}
