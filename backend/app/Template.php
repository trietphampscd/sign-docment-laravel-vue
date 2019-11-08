<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'templates';

    protected $fillable = [
        'template_id', 'template_name', 'template_file', 'creator_id', 'first_signer_id'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'template_id' => 'required|unique:templates',
        'template_name' => 'required|unique:templates',
        'template_file' => 'required|unique:templates',
        'creator_id' => 'required',
    );
}
