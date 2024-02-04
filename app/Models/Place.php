<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function voivodeship()
    {
        return $this->belongsTo(Voivodeship::class, 'voivodeship_id', 'voivodeship_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'stores_places', 'place_id', 'store_id');
    }
}
