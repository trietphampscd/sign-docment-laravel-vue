<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'countries';

    protected $fillable = [
        'country_code', 'country_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'country_code' => 'required|unique:countries',
        'country_name' => 'required|unique:countries',
    );
}
