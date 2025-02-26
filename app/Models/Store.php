<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Store extends Model implements HasMedia
{

    use HasTranslations, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'minimum_cart_value',
        'latitude',
        'longitude',
        'location',
        'working_hours',
        'delivery_range', // Kilometers
        'active',
    ];

    protected $translatable = ['name', 'address'];

    // protected $appends = ['logo', 'cover'];

    protected $hidden = ['pivot'];

    protected $casts = [
        'working_hours' => 'array',
        'active' => 'boolean',
    ];

    protected $appends = [
        "location"
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getLogoAttribute()
    {
        $icon = $this->getFirstMediaUrl("logo");
        return $icon ?? 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }

    public function getCoverAttribute()
    {
        $icon = $this->getFirstMediaUrl("cover");
        return $icon ?? 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }

    public function location(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => json_encode([
                'lat' => (float) $attributes['latitude'],
                'lng' => (float) $attributes['longitude'],
            ]),
            set: fn ($value) => [
                'latitude' => $value['lat'],
                'longitude' => $value['lng'],
            ],
        );
    }
}
