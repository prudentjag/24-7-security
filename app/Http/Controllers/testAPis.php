<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Services\ResponseService;
use App\Services\SmsService;
use Illuminate\Http\Request;

class testAPis extends Controller
{
    protected $smsService;
    protected $responseService;

    public function __construct(SmsService $smsService, ResponseService $responseService)
    {
        $this->middleware('auth.role:admin', ['except' => ['Otp']]);
        $this->smsService = $smsService;
        $this->responseService = $responseService;
    }

    public function Otp(Request $request) {
        $sms = $this->smsService->SendSms($request->phone);
        if($sms['status'] == 200){
           $otp = Otp::create([
                'pin_id' => $sms['pinId'],
                'user_id' => 1,
            ]);

            if($otp) {
               return $this->responseService->success($otp, 'Check sms for OTP');
            }
        }
    }
}
