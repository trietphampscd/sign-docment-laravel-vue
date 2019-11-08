<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'plan_types';

    protected $fillable = [
        'plan_name', 'price_usd', 'discount_yearly', 'balances_month'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'plan_name' => 'required|unique:plan_types',
    );
}
