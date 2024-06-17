<?php

namespace App\Enums;

enum Roles :string {
    case user = 'user';
    case landlord = 'landlord';
    case ceo = 'ceo';
    case admin = 'admin';
}
