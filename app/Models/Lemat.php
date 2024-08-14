<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lemat extends Model
{
    use HasFactory;

    protected $fillable = ['word', 'lemma'];
}
