<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function leaflets(): BelongsToMany
    {
        return $this->belongsToMany(Leaflet::class, 'leaflets_products', 'product_id', 'leaflet_id');
    }

    public function leaflet_categories(): BelongsToMany
    {
        return $this->belongsToMany(LeafletCategory::class, 'leaflet_category_products', 'product_id', 'category_id');
    }

    public function leaflet_subcategories(): BelongsToMany
    {
        return $this->belongsToMany(LeafletCategory::class, 'leaflet_category_products', 'product_id', 'subcategory_id');
    }


    public function childrenCategories(): HasMany
    {
        return $this->hasMany(Product::class)->with('leafletCategories');
    }
}
