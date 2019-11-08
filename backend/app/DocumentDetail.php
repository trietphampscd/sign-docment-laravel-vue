<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentDetail extends Model
{
    protected $table = "documents_details";
    
    protected $primaryKey = 'document_id';
    public $incrementing = false;

    protected $fillable = [
        'document_id','creator_id','name', 'expiration_days', 'action', 'status'
    ];

    public function documents(){
        return $this->hasMany('App\Document', 'document_id', 'document_id');
    }

    public function create(){
        return $this->belongsTo('App\User', 'creator_id', 'id');
    }
}
