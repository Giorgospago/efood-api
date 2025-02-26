<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{

    use HasTranslations, InteractsWithMedia;

    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'store_id',
        'product_category_id',
        'active',
        'sort',
    ];

    protected $casts = [
        'active' => 'boolean',
        'sort' => 'integer',
        'price' => 'float',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function getMainImageAttribute()
    {
        $image = $this->getMedia("gallery")
            ->sortByDesc("order_column")
            ->first();

        return $image?->getUrl() ?? 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }

    public function getGalleryAttribute()
    {
        $gallery = $this->getMedia("gallery")
            ->sortByDesc("order_column");

        return $gallery ?? ['https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png'];
    }
}
