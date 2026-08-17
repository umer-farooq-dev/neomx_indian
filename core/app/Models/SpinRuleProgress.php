<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpinRuleProgress extends Model
{
    protected $table = 'spin_rule_progress';

    protected $fillable = ['user_id', 'rule_id', 'triggered_count'];
}
