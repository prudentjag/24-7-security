<?php

namespace App\Services;

use App\Http\Response;

/**
 * Class ResponseService.
 */
class ResponseService
{
    /**
     * Format a success response.
     *
     * @param  mixed  $data
     * @return \Illuminate\Http\JsonResponse
     */
     public function success($data, ?string $message = null, int $statusCode = Response::HTTP_CREATED)
    {
        $responseData = ['status' => true];

        if ($message !== null) {
            $responseData['message'] = $message;
        }

        $responseData['data'] = $data;

        return response()->json($responseData, $statusCode);
    }

    /**
     * Format an error response.
     *
     * @param  string  $message
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function error($message, $statusCode)
    {
        return response()->json([
            'status' => false,
            'error' => $message,
        ], $statusCode);
    }
}
