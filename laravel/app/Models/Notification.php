<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;
    protected $table = 'notification';
    protected $fillable = ['attr1', 'attr2', 'attr3'];
    use HasFactory;

    public function userNotification(): BelongsTo {
        return $this-> belongsTo(User::class);
    }

    //???????????????????????????
    public function winner(): BelongsTo{
        return $this-> belongsTo(Notification::class);
    }
    
    //???????????????????????????
    public function endAuction(): BelongsTo{
        return $this-> belongsTo(Notification::class);
    }

    //???????????????????????????
    public function outBid(): BelongsTo{
        return $this-> belongsTo(Notification::class);
    }

}
