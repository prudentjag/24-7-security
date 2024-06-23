<?php
namespace App\Services;

class EmailtokenService {

    public function GenerateToken($user) {
        $token = generate_tokens(10000,99999, 'otps', 'email_token');
        
    }
}
