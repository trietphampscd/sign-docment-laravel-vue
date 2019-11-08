<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'clients';

    protected $fillable = [
        'user_id', 'signature_type', 'text', 'font_face', 'uploaded_url'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'user_id' => 'required',
        'signature_type' => 'required',
        'uploaded_url' => 'required',
    );

    public function user() {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

}
