<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteType extends Model
{
    use HasFactory;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|mixed
     */

    protected $guarded = [];

    public function descriptions()
    {
        return $this->hasMany(SiteDescription::class, 'type_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(SiteQuestionAnswer::class, 'type_id', 'id');
    }

    public function meta()
    {
        return $this->hasMany(SiteMeta::class, 'type_id', 'id');
    }

}
