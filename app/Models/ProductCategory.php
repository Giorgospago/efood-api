<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasTranslations;

    protected $translatable = ['name'];

    protected $fillable = [
        'name',
        'store_id',
        'sort',
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
