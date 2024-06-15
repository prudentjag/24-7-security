<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'phone',
        'role',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

     /**
     * Convert phone number to international format.
     *
     * @param string $phone
     * @return string|null
     */
    public static function convertToInternationalFormat($phone)
    {
        try {
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parsedNumber = $phoneUtil->parse($phone, 'NG'); // 'NG' for Nigeria, adjust based on your requirement
            $formattedNumber = $phoneUtil->format($parsedNumber, PhoneNumberFormat::E164);
            return str_replace('+', '', $formattedNumber);
        } catch (\libphonenumber\NumberParseException $e) {
            return null; // Handle the exception as per your needs
        }
    }
}
