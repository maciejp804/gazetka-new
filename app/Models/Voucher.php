<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Voucher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function voucherStore(): BelongsTo
    {
        return $this->belongsTo(VoucherStore::class,'program_id', 'program_id');
    }
}
