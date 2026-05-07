<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Message;
use App\Models\ReferralIncome;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'pan_card_no',
        'address',
        'pincode',
        'password',
        'sponsor_id',
        'parent_id',
        'level',
        'position',
        'connection',
        'join_amount',
        'total_income',
        'role',
        'user_image',
        'package_id',
        'rank_id',
        'gender',
        'is_pair_matched',
        'mother_id',
        'rank_status',
        'assigned_board_member_id',
        'fill_preference',
        'registration_source',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship: Sponsor (Parent User)
     */
    public function sponsor()
    {
        return $this->belongsTo(User::class, 'sponsor_id');
    }

    /**
     * Relationship: Parent (Direct Upline)
     */
    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Relationship: Downlines (Users Sponsored)
     */
    public function downlines()
    {
        return $this->hasMany(User::class, 'sponsor_id');
    }
    public function children()
    {
        return $this->hasMany(User::class, 'parent_id');
    }

    // Define left and right children for binary tree
    public function leftChild()
    {
        return $this->hasOne(User::class, 'parent_id')->where('position', 'left');
    }

    public function rightChild()
    {
        return $this->hasOne(User::class, 'parent_id')->where('position', 'right');
    }
    public function sponsors()
    {
        return $this->belongsTo(User::class, 'sponsor_id')->with('sponsors');
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class, 'msg_from_id')
            ->latest('created_at');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id'); // Adjust 'package_id' as per your DB schema
    }

    public function leftDownlineCount()
    {
        // Base case: if there's no left child, return 0
        if (!$this->leftChild) {
            return 0;
        }

        // Recursively count left children and their left and right downlines
        return 1 + $this->leftChild->leftDownlineCount() + $this->leftChild->rightDownlineCount();
    }

    /**
     * Recursively count the number of users in the right downline.
     */
    public function rightDownlineCount()
    {
        // Base case: if there's no right child, return 0
        if (!$this->rightChild) {
            return 0;
        }

        // Recursively count right children and their left and right downlines
        return 1 + $this->rightChild->leftDownlineCount() + $this->rightChild->rightDownlineCount();
    }

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class, 'user_id');
    }
    public function referralIncomes()
    {
        return $this->hasMany(ReferralIncome::class, 'sponsor_id');
    }

    public function silvers()
    {
        return $this->hasMany(User::class, 'parent_id')->where('user_rank', 'Silver')->take(3);
    }

    public function rank()
    {
        return $this->belongsTo(Rank::class, 'rank_id');
    }

    public function userBasicPackages()
    {
        return $this->hasMany(BasicRankAchieve::class, 'user_id');
    }
}
