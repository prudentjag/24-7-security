<?php

namespace App\Models;

use App\Enums\PropertyTypes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class property extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'name',
        'type',
        'address',
        'number_of_units',
    ];

    protected $casts = [
        'type' => PropertyTypes::class,
    ];

    public function landlord()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function units()
    {
        return $this->hasMany(Units::class);
    }
}
