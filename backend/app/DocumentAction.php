<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentAction extends Model
{
    protected $table = "document_actions";

    protected $fillable = [
        'document_id','page','rotate', 'delete', 'created_at', 'updated_at'
    ];
}
