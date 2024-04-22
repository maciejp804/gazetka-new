<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Map extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function stores()
    {
        return $this->hasOne(Store::class, 'id','store_id');
    }

    public function places()
    {
        return $this->hasOne(Place::class, 'id', 'place_id');
    }
}
