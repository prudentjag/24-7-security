<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\OtpVerify;
use App\Http\Response;
use App\Models\Otp;
use App\Models\User;
use App\Services\ResponseService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class VerifyotpController extends Controller
{
    protected $smsService;
    protected $responseService;

    public function __construct(SmsService $smsService, ResponseService $responseService)
    {
        $this->smsService = $smsService;
        $this->responseService = $responseService;
    }

    public function VerifyOtp(OtpVerify $request) {
        $data = $request->validated();
        $otp = Otp::where('user_id', $data['user_id'])->first(); //Get the user id
        if (!$otp) {
            return $this->responseService->error('Invalid user ID or OTP not found.', Response::HTTP_BAD_REQUEST);
        }
        $verify = $this->smsService->VerifyOtp( $otp->pin_id, $data['token'],);
         if ($verify['verified'] == "True") {
            $otp->delete();
            return $this->responseService->success(null, 'OTP verified successfully.');
        } else {
            $otp->delete();
            return $this->responseService->error($verify['verified'], Response::HTTP_BAD_REQUEST);
        }
    }

    public function ResendOtp(Request $request) {
        $converted_number = User::convertToInternationalFormat($request->phone);
        if (!$converted_number) {
            return $this->responseService->error('Invalid phone number format.', Response::HTTP_BAD_REQUEST);
        }
        $sms = $this->smsService->SendSms($request->phone);
        $user = User::where('phone', $converted_number)->first();
        if (!$user) {
            return $this->responseService->error('Invalid Phone Number or User not found.', Response::HTTP_BAD_REQUEST);
        }
        if($sms['status'] == 200){
           $otp = Otp::create([
                'pin_id' => $sms['pinId'],
                'user_id' => $user->id,
            ]);

            if($otp) {
               return $this->responseService->success($otp, 'An OTP has been sent to your SMS');
            }
        }
    }
}
