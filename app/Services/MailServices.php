<?php
namespace App\Services;

use App\Mail\Otpmail;
use App\Mail\PasswordReset;
use Illuminate\Support\Facades\Mail;

class MailServices
{
    public function Mailer(array $data, string $mailClass) {
        $sendmail = Mail::to($data['email'])->send(new $mailClass($data));
        if($sendmail){
            return true;
        }else{
            return false;
        }
    }
}

