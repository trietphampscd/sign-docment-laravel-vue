<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'states';

    protected $fillable = [
        'country_id', 'state_code', 'state_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'country_id' => 'required',
        'state_code' => 'required|unique:states',
        'state_name' => 'required|unique:states',
    );
}
