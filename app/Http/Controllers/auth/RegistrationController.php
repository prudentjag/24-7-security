<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration;
use App\Http\Response;
use App\Models\Otp;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;
use App\Services\MailServices;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    protected $smsService;
    protected $responseService;
    protected $phoneNumberService;
    protected $mailServices;

    public function __construct(
        SmsService $smsService,
        ResponseService $responseService,
        MailServices $mailServices
        )
    {
        $this->smsService = $smsService;
        $this->responseService = $responseService;
        $this->mailServices = $mailServices;
    }

    public function register(Registration $request)
    {
        $data = $request->validated();
        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => User::convertToInternationalFormat($data['phone']),
            'role' => $data['role'] ?? 'user',
            'password' => Hash::make($data['password']),
        ]);

        $otp = $this->sendOtp($data['phone'], $user->id);

        // Generate email token
        $emailToken = generate_tokens(100000, 999999, 'otps', 'email_token');
        $emailData = array_merge(['otp' => $emailToken], $data);
        $storemailtoken = $this->saveMailToken($emailToken, $user->id);

        // Send OTP email
        $mail = $this->mailServices->Mailer($emailData, \App\Mail\Otpmail::class);

        if ($otp ) {
            return $this->responseService->success($otp, 'Check SMS for OTP and complete your profile');
        } else {
            return $this->responseService->error('Failed to send OTP', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function sendOtp($phone, $userId)
    {
        $sms = $this->smsService->SendSms($phone);
        if ($sms['status'] == 200) {
            $otp = Otp::create([
                'pin_id' => $sms['pinId'],
                'user_id' => $userId,
                'expires_at' => Carbon::now()->addMinutes(10)
            ]);
            return $otp;
        }

        return null;
    }

    private function saveMailToken($pin, $userId){
        $otp = Otp::updateOrCreate(['user_id' => $userId],[
                'email_token' => $pin,
                'user_id' => $userId,
            ]);

            if (!$otp) {
                return false;
            }
            return $otp;
    }


    public function LandlordRegistration(Registration $request){
        $data = $request->validated();
        $filteredData = filter_empty_values($data);
        $user = User::create([
           'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => User::convertToInternationalFormat($data['phone']),
            'role' => $data['role'] ?? 'user',
            // 'password' => Hash::make($data['password'] ?? \Str::random(7)),
        ]);
        $data = array_merge(['Otp'=> generate_tokens(10000,99999, 'otps', 'email_token'), $user]);
        if (!$data) {
            return $this->responseService->error('Unable to generate token', Response::HTTP_BAD_REQUEST);
        }
        $mail = $this->mailServices->Mailer($data, 'Otpmail');
        if ($mail) {
            return $this->responseService->success(null, 'An otp has been sent to your email address');
        }
    }


}
