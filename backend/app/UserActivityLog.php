<?php

namespace App;

use Illuminate\Notification\Notifiable;
use Illuminate\Database\Eloquent\Model;

class UserActivityLog extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'user_activity_logs';

    protected $fillable = [
        'activity_type', 'user_id', 'activity_date', 'description'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'activity_type' => 'required',
        'user_id' => 'required',
        'activity_date' => 'required',
        'description' => 'required',
    );
}
