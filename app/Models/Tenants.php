<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tenants extends Model
{
    use HasFactory;

    protected $fillable = [
        'landlord_id',
        'tenant_id',
    ];

    /**
     * Get the user that owns the Tenants
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'landlord_id', 'other_key');
    }
}
