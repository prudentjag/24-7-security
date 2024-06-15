<?php

namespace App\Services;

use App\Http\Response;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

/**
 * Class TransactionsChecker.
 */
class SmsService
{
    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }
    public function sendSms($phone)
    {
        $converted_number = User::convertToInternationalFormat($phone);
        if (!$converted_number) {
            return $this->responseService->error('Invalid phone number format.', Response::HTTP_BAD_REQUEST);
        }
        $data = [
            "api_key" => env('SMS_SECRET'),
            "message_type" => "NUMERIC",
            "to" => (int)$converted_number,
            "from" => "AbiaIRS",
            "channel" => "dnd",
            "pin_attempts" => 5,
            "pin_time_to_live" => 5,
            "pin_length" => 6,
            "pin_placeholder" => "< 123456 >",
            "message_text" => "Welcome to 24/7 security. Activate your account with this pin < 123456 >",
            "pin_type" => "NUMERIC",
        ];

         $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('BASE_URL_SMS') . 'otp/send', $data);
        if ($response->successful()) {
            return $response->json();
        }

        return response()->json(['error' => 'Failed to send SMS'], $response->status());
    }


    public function Verifyotp($pin_id , $pin)
    {
        $data = [
            "api_key" => env('SMS_SECRET'),
            "pin_id" => $pin_id,
            "pin" => $pin,
        ];

         $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post(env('BASE_URL_SMS') . 'otp/verify', $data);

        //dd($response->json());
        return $response->json();
    }

}
