<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class SignRequest extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'sign_requests';

    protected $fillable = [
        'document_id', 'request_type', 'signer_id', 'signed_at', 'signed_file', 'password', 'expiration_days', 'comment', 'next_request_id'
    ];

    protected $hidden = [
        'password'
    ];

    private $rules = array(
        'document_id' => 'required',
        'request_type' => 'required',
        'signer_id' => 'required',
        'expiration_days' => 'required',
    );
}
