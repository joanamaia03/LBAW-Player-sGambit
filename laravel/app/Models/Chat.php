<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chat extends Model
{
    public $timestamps = false;
    protected $table = 'chat';
    protected $fillable = ['attr1', 'attr2', 'attr3']; //??????????????
    use HasFactory;
    //?????????????????????????
    public function auctionWinnerChats(): BelongsTo {
        return $this->belongsTo(User::class, 'auctionWinner');
    }
    //documentação de laravel
    public function auctionCreatorsChats(): BelongsTo {
        return $this->belongsTo(User::class, 'auctionCreator');
    }

    public function getChats() {
        return $this->auctionWinnerChats->merge($this->auctionCreatorsChats);
    }
    //?????????????????????????
    public function auctionChats(): BelongsTo {
        return $this->belongsTo(Auction::class);
    } //disjoint complete juntar tabelas
}
