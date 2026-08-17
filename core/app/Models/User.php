<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, UserNotify;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','ver_code','balance','referral_balance','reward_balance','kyc_data'
    ];

    const WALLET_MAIN = 'main';
    const WALLET_REFERRAL = 'referral';
    const WALLET_REWARD = 'reward';

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'address' => 'object',
        'kyc_data' => 'object',
        'ver_code_send_at' => 'datetime'
    ];


    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id','desc');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function referrals()
    {
        return $this->hasMany(User::class, 'ref_by');
    }

    // Direct referrals who have made at least one successful deposit.
    public function activeReferralsCount()
    {
        return $this->referrals()
            ->whereHas('deposits', function ($query) {
                $query->where('status', Status::PAYMENT_SUCCESS);
            })
            ->count();
    }

    // Full downline (every level), each row tagged with its level number.
    public function downlineTree($maxDepth = 15)
    {
        $rows = \Illuminate\Support\Facades\DB::select("
            WITH RECURSIVE downline AS (
                SELECT id, username, firstname, lastname, ref_by, status, created_at, 1 AS lvl
                FROM users WHERE ref_by = ?
                UNION ALL
                SELECT u.id, u.username, u.firstname, u.lastname, u.ref_by, u.status, u.created_at, d.lvl + 1
                FROM users u
                INNER JOIN downline d ON u.ref_by = d.id
                WHERE d.lvl < ?
            )
            SELECT * FROM downline ORDER BY lvl, id
        ", [$this->id, $maxDepth]);

        return collect($rows);
    }

    public function teamSize()
    {
        return $this->downlineTree()->count();
    }

    // Lifetime referral commission earned (unaffected by later withdrawals from the Referral Wallet).
    public function totalReferralEarnings()
    {
        return $this->transactions()->where('remark', 'referral_commission')->sum('amount');
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status','!=',Status::PAYMENT_INITIATE);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn () => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn () => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev',Status::VERIFIED)->where('sv',Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance','>', 0);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }

    public static function walletColumn($walletType)
    {
        return match ($walletType) {
            self::WALLET_REFERRAL => 'referral_balance',
            self::WALLET_REWARD   => 'reward_balance',
            default               => 'balance',
        };
    }

    public static function walletLabel($walletType)
    {
        return match ($walletType) {
            self::WALLET_REFERRAL => 'Referral Wallet',
            self::WALLET_REWARD   => 'Reward Wallet',
            default               => 'Main Wallet',
        };
    }

    public function walletBalance($walletType)
    {
        $column = self::walletColumn($walletType);
        return $this->$column;
    }

}
