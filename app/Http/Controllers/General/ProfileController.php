<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use App\Http\Response;
use App\Models\Profile;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected $responseService;
    public function __construct(ResponseService $responseService)
    {
        $this->middleware('auth.role:user');
        $this->responseService = $responseService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profile = Profile::with('user')->first();
        return $profile ? $this->responseService->success($profile, 'Profile gotten') :
                        $this->responseService->error('Profile not found', Response::HTTP_BAD_REQUEST);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProfileRequest $request , Profile $profile)
    {
        $data = $request->validated();
        $filteredData = filter_empty_values($data); // Removes the empty the empty keys from array
        $addProfile = $profile->updateOrCreate(
            [
                'user_id' => $data['user_id'],
            ],
            $filteredData
        );

        return $addProfile ? $this->responseService->success($addProfile, 'Profile Updated') :
                        $this->responseService->error('Error updating Profile', Response::HTTP_BAD_REQUEST);

    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Profile $profile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile)
    {
        //
    }
}
