<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'plans';

    protected $fillable = [
        'user_id', 'plan_type_id', 'currency_id', 'is_yearly_plan', 'started_at', 'is_ended', 'balances_used', 'coupon_discount'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'user_id' => 'required',
        'plan_type_id' => 'required',
        'currency_id' => 'required',
        'is_yearly_plan' => 'required',
    );
}
