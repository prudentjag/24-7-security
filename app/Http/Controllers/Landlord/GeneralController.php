<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration;
use App\Http\Response;
use App\Models\units;
use App\Models\User;
use App\Services\MailServices;
use App\Services\ResponseService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use illuminate\Support\Str;

class GeneralController extends Controller
{
    protected $responseService;
    protected $mailServices;

    public function __construct(ResponseService $responseService, MailServices $mailServices)
    {
        $this->middleware('auth.role:landlord');
        $this->$responseService = $responseService;
        $this->mailServices = $mailServices;
    }

    public function AddTenants(Registration $request)
    {
        $data = $request->validated();

        $user = User::create([
            'first_name' => $data['first_name'],
            'middle_name' => $data['middle_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => User::convertToInternationalFormat($data['phone']),
            'role' => $data['role'] ?? 'tenant',
        ]);

        // the user should select a unit from the listed list
        $unit = Units::findOrFail($data['unit_id']);
        $unit->occupants()->attach($user->id, [
            'start_date' => now(),
            'end_date' => null
        ]);

        $sendtoken = $this->Generate_reset_Token($user->email);
        if (!$sendtoken) {
            return $this->responseService->error('Unable to generate token', Response::HTTP_BAD_REQUEST);
        }

        return $this->responseService->success([
            'user_id' => $user->id,
            'unit_id' => $unit->id
        ], 'Tenant added successfully');
    }

    // This generates a reset password token for the registered user to reset his password
    public function Generate_reset_Token($email){
        $randomToken = Str::random(25);
        $token = generate_tokens(null, null,'password_reset_tokens', 'token', $randomToken);
        $addtoken = DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);
        if (!$addtoken) {
            return $this->responseService->error('Error inserting token', Response::HTTP_BAD_REQUEST);
        }
        $mail = $this->mailServices->Mailer(['token' => $token, 'email' => $email], 'passwordrest');

        return $mail ? true : false;
    }

    public function PropertyUnits()
    {
        $user = Auth::user();
        $ownedProperties = $user->ownedProperties()->with('units')->get();

        $formattedProperties = $ownedProperties->map(function ($property) {
            return [
                'property_id' => $property->id,
                'property_name' => $property->name,
                'units' => $property->units->map(function ($unit) {
                    return [
                        'unit_id' => $unit->id,
                        'unit_number' => $unit->unit_number,
                        // Add any other unit details you want to include
                    ];
                }),
            ];
        });

        return $this->responseService->success([
            'properties' => $formattedProperties,
        ], 'Properties and units retrieved successfully');
    }
}
