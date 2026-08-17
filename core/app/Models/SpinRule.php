<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class SpinRule extends Model
{
    use GlobalStatus;

    const SIGNUP = 'signup';
    const REFERRAL_COUNT = 'referral_count';
}
