<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'dob' => 'required|date',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
            'nin' => 'required|integer|min:10|max:10',
            'avatar' => 'image', // Assuming avatar is an image file
            'plate_number' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function messages(): array
    {
        return [
            'dob.required' => 'Date of birth is required',
            'dob.date' => 'Date of birth must be a valid date',
            'address.required' => 'Address is required',
            'address.string' => 'Address must be a string',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender must be either male or female',
            'nin.required' => 'NIN is required',
            'nin.integer' => 'NIN must be in Numeric form',
            'nin.max' => 'Nin cannot be more than 10 digits',
            'nin.min' => 'NIN cannot be less than 10 digits',
            'avatar.image' => 'Avatar must be an image file',
            'plate_number.required' => 'Plate number is required',
            'plate_number.string' => 'Plate number must be a string',
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User ID must exist in the users table'
        ];
    }
}
