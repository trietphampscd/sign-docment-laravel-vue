<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailStyle extends Model
{
    protected $table = "email_styles";

    protected $fillable = [
        'client_id',
        'logo_image',
        'siging_request_message',
        'edge_color',
        'button_color',
        'button_text_color'
    ];

}
