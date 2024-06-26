<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryStore::class, 'category_store_id');
    }

    public function leaflet(): HasMany
    {
        return $this->hasMany(Leaflet::class);
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'stores_places', 'store_id', 'place_id');
    }

    public function markers()
    {
        return $this->hasMany(Map::class);
    }
}
