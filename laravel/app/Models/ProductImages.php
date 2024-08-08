<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    public $timestamps = false;
    protected $table = 'product_images';
    protected $fillable = ['attr1', 'attr2', 'attr3'];
    use HasFactory;

    public function auctionImage(): BelongsTo{
        return $this-> belongsTo(Auction::class);
    }

    //???????????????????????????
    public function jpg(): BelongsTo{
        return $this-> belongsTo(ProductImages::class);
    }

    //???????????????????????????
    public function png(): BelongsTo{
        return $this-> belongsTo(ProductImages::class);
    }
}
