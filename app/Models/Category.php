<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Support\Facades\Cache;

class Category extends Model implements HasMedia
{
    use HasTranslations, InteractsWithMedia;

    protected $translatable = ['name'];

    protected $fillable = ['name'];

    // protected $appends = ['icon'];

    protected $hidden = ['pivot'];

    protected static function booted()
    {
        static::created(function (Category $category) {
            Cache::forget('categories');
        });
        static::updated(function (Category $category) {
            Cache::forget('categories');
        });
        static::deleted(function (Category $category) {
            Cache::forget('categories');
        });
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class);
    }

    public function getIconAttribute()
    {
        $icon = $this->getFirstMediaUrl("icon");
        return $icon ?? 'https://static.vecteezy.com/system/resources/previews/049/351/008/non_2x/microsoft-lists-icon-logo-symbol-free-png.png';
    }
}
