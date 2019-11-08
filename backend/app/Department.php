<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'departments';

    protected $fillable = [
        'department_name'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'department_name' => 'required|unique:departments',
    );
}
