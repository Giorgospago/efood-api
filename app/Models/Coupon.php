<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'store_id',
        'name',
        'description',
        'image',
        'code',
        'type',
        'value',
        'start_date',
        'end_date',
        'active',
    ];

    protected $casts = [
        'value' => 'float',
        'active' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
