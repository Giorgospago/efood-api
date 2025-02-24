<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasTranslations;

    protected $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'description',
        'price',
        'store_id',
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
}
