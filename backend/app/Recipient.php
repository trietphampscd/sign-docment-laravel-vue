<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recipient extends Model
{
    protected $table = "recipients";

    protected $fillable = [
        'email', 'phone', 'user_id'
    ];

    public function user(){
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
