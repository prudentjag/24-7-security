<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Http\Response;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{

    protected $responseService;

    public function __construct(ResponseService $responseService)
    {
        $this->responseService = $responseService;
    }
     public function login(Request $request) {
        $login = $request->input('login');
        $password = $request->input('password');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $credentials = [$fieldType => $login, 'password' => $password];

        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->responseService->error('Invalid login details', Response::HTTP_UNAUTHORIZED);
        }

        $user = JWTAuth::user();

        return response()->json([
            'message' => 'Login Successful',
            'user' => $user,
            'token' => $token,
            'status' => true,
        ]);
    }

}
