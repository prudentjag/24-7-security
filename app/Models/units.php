<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class units extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'unit_number',
        'bedrooms',
        'type'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function occupants()
    {
        return $this->belongsToMany(User::class, 'unit_user')
                    ->withPivot('start_date', 'end_date')
                    ->withTimestamps();
    }

    
}
