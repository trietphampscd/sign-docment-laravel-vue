<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class TemplateSigner extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'template_signers';

    protected $fillable = [
        'template_id', 'user_id', 'next_signer_id', 'comment'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'template_id' => 'required',
        'user_id' => 'required',
    );
}
