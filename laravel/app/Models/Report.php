<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    public $timestamps = false;
    protected $table = 'report';
    protected $fillable = ['attr1', 'attr2', 'attr3'];
    use HasFactory;

    public function report(): BelongsTo{
        return $this-> belongsTo(User::class);
    }

    //???????????????????????????
    public function auctionReport(): BelongsTo{
        return $this-> belongsTo(Report::class);
    }
    
    //???????????????????????????
    public function userReport(): BelongsTo{
        return $this-> belongsTo(Report::class);
    }
}