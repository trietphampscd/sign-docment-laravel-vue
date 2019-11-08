<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Industries extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'industries';

    protected $fillable = [
        'industry_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'industry_name' => 'required|unique:industries',
    );
}
