<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerify extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'token' => 'required|size:6'
        ];
    }

    public function messages(): array
    {
         return [
            'user_id.required' => 'User ID field must not be empty.',
            'user_id.exists' => 'The selected user ID is invalid.',
            'token.required' => 'Token field must not be empty.',
            'token.size' => 'A 6-digit token is expected.',
        ];
    }
}
