<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\FileController;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;
    protected $primaryKey = 'user_id';
    /**
     * The attributes that are mass assignable.
     *??????????????????????????????????
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_admin',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *????????????????????????
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *????????????????????
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public function auctions(): hasMany {
        return $this->hasMany(Auction::class);
    }

    public function userBids(): hasMany {
        return $this->hasMany(Bid::class);
    }
    //???????????????????????????
    public function chats(): hasMany {
        return $this->hasMany(Chat::class, 'auctionWinner')->orWhere('chats.auctionCreator', $this->id);
    }

    public function bans(): hasOne {
        return $this->hasOne(Ban::class);
    }

    public function reportedUser(): hasMany {
        return $this->hasMany(UserReport::class);
    }
    //???????????????????????????
    public function userPayments(): hasMany {
        return $this->hasMany(Payment::class);
    }

    public function reports(): hasMany {
        return $this->hasMany(Report::class);
    }

    public function notifications(): hasMany {
        return $this->hasMany(Notification::class);
    }

    public function getProfileImage() {
        return FileController::get('profile', $this->user_id);
    }
}
