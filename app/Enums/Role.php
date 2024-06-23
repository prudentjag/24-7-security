<?php

namespace App\Enums;

enum Role :string {
    case user = 'user';
    case landlord = 'landlord';
    case ceo = 'ceo';
    case admin = 'admin';
}
