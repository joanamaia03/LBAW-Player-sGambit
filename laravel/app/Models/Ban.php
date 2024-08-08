<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ban extends Model
{
    public $timestamps = false;
    protected $table = 'ban';
    protected $primaryKey = 'ban_id';
    protected $fillable = ['attr1', 'attr2', 'attr3']; //??????????????
    use HasFactory;

    public function userBans(): BelongsTo {
        return $this->belongsTo(User::class);
    }
}
