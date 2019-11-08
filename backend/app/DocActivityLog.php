<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class DocActivityLog extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'doc_activity_logs';

    protected $fillable = [
        'activity_type', 'doc_id', 'actor_id', 'activity_date', 'description'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'activity_type' => 'required',
        'doc_id' => 'required',
        'actor_id' => 'required',
        'activity_date' => 'required',
    );
}
