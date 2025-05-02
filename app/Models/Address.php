<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'street',
        'number',
        'city',
        'postal_code',
        'latitude',
        'longitude',
        'phone',
        'floor',
        'door',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string {
        return $this->street . " " . $this->number . ", " . $this->city . " " . $this->postal_code;
    }
}
