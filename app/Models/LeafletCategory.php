<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeafletCategory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function leaflets(): HasMany
    {
        return $this->hasMany(Leaflet::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'leaflet_category_products', 'category_id', 'product_id');
    }

    public function parents(): HasMany
    {
        return $this->hasMany(LeafletCategory::class, 'parent_id', 'parent_id');
    }

    public function childrenCategories(): HasMany
    {
        return $this->hasMany(LeafletCategory::class,'parent_id')->with('parents');
    }
}
