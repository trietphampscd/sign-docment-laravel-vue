<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentRecipient extends Model
{
    protected $table = "document_recipient";

    protected $fillable = [
        'document_id', 'recipient_id'
    ];

}
