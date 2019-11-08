<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'languages';

    protected $fillable = [
        'language_code', 'language_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'language_code' => 'required|unique:languages',
        'language_name' => 'required|unique:languages',
    );
}
