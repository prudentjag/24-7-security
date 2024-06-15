<?php

namespace App\Services;

class PhoneNumberService
{
    public function convertToInternationalFormat($phone)
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Check if the phone number starts with '0'
        if (substr($phone, 0, 1) === '0') {
            // Remove the leading '0' and prepend '234'
            $phone = '234' . substr($phone, 1);
        }

        return $phone;
    }
}
