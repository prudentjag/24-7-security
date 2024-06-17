<?php

namespace App\Models;

use App\Models\Scopes\SelfData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasFactory;

    protected $fillable =[
        'dob',
        'address',
        'gender',
        'nin',
        'avatar',
        'plate_number',
        'user_id'
    ];


    /**
     * Get the user that owns the Profile
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new SelfData);
    }
}
