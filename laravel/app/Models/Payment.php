<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public $timestamps = false;
    protected $table = 'payment';
    protected $fillable = ['attr1', 'attr2', 'attr3']; //?????????????
    use HasFactory;

    public function paymentsByUser(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function paymentsForAuction(): BelongsTo {
        return $this->belongsTo(Auction::class);
    }
}
