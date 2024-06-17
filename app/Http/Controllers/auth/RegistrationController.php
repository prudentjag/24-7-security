<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration;
use App\Http\Response;
use App\Models\Otp;
use App\Models\User;
use App\Services\PhoneNumberService;
use App\Services\ResponseService;
use App\Services\SmsService;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    protected $smsService;
    protected $responseService;
    protected $phoneNumberService;

    public function __construct(
        SmsService $smsService,
        ResponseService $responseService,
        )
    {
        $this->smsService = $smsService;
        $this->responseService = $responseService;
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
        if ($otp) {
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
            ]);
            return $otp;
        }

        return null;
    }
}
