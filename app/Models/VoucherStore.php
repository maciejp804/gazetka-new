<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VoucherStore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class,'program_id', 'program_id');
    }
}
