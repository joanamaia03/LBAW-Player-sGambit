<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Http\Controllers\FileController;

class Auction extends Model
{
    public $timestamps = false;
    protected $primaryKey = 'auction_id';
    protected $table = 'auction';
    protected $fillable = ['auction_id']; //??????????????
    use HasFactory;

    public function creators(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function auctionBids(): hasMany {
        return $this->hasMany(Bid::class);
    }

    public function auctionPayments(): hasOne {
        return $this->hasOne(Payment::class);
    }
    //????????????????????????
    public function chats(): hasOne {
        return $this->hasOne(Chat::class);
    } //pode ser null

    public function winnerNotifications(): hasOne {
        return $this->hasOne(WinnerNotification::class);
    }

    public function endAuctionNotifications(): hasOne {
        return $this->hasOne(EndAuctionNotification::class);
    }
    
    public function productImages(): hasMany {
        return $this->hasMany(ProductImage::class);
    }

    public function reportedAuctions(): hasMany {
        return $this->hasMany(AuctionReport::class);
    }
    //????????????????????????

    public function getAuctionImage() {
        return FileController::get('auction', $this->auction_id);
    }

}
