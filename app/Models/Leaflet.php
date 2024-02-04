<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Leaflet extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function leaflet_categories()
    {
        return $this->hasMany(LeafletCategory::class, 'leaflet_category_id', 'category_index');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'leaflets_products', 'leaflet_id', 'product_id');
    }
}
