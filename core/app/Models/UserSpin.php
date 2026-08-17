<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpin extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(SpinReward::class, 'reward_id');
    }

    public function rule()
    {
        return $this->belongsTo(SpinRule::class, 'rule_id');
    }
}
