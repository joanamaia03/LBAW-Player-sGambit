<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    public $timestamps = false;
    protected $table = 'bid';
    protected $primaryKey = 'bid_id';
    protected $fillable = ['attr1', 'attr2', 'attr3']; //??????????????
    use HasFactory;

    public function bidCreator(): BelongsTo {
        return $this-> belongsTo(User::class);
    }

    public function bidAuction(): BelongsTo {
        return $this-> belongsTo(Auction::class);
    }

    public function outbidNotification(): hasOne {
        return $this->hasOne(OutbidNotification::class);
    }
}
