<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{

    use HasTranslations;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'minimum_cart_value',
        'latitude',
        'longitude',
        'working_hours',
        'delivery_range', // Kilometers
        'active',
    ];

    protected $translatable = ['name', 'address'];

    protected $appends = ['logo', 'cover'];

    protected $casts = [
        'working_hours' => 'array',
        'active' => 'boolean',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function getLogoAttribute()
    {
        return 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }

    public function getCoverAttribute()
    {
        return 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }
}
