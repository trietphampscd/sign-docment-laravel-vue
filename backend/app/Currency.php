<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'currencies';

    protected $fillable = [
        'currency_code', 'currency_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'currency_code' => 'required|unique:currencies',
        'currency_name' => 'required|unique:currencies',
    );
}
