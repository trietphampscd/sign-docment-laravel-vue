<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'documents';

    protected $fillable = [
        'document_id', 'document_name', 'document_file', 'creator_id', 'doc_folder_id', 'expiration_days', 'action', 'status', 'comment',
        'first_request_id', 'password', 'signed_file'
    ];

    protected $hidden = [
        'password'
    ];

    private $rules = array(
        'document_id' => 'required|unique:documents',
        'document_name' => 'required|unique:documents',
        'document_file' => 'required|unique:documents',
        'creator_id' => 'required',
        'expiration_days' => 'required',
        'action' => 'required',
        'status' => 'required',
    );

    public function recipients(){
        return $this->belongsToMany('App\Recipient');
    }

    public function annotations(){
        return $this->hasMany('App\Annotation','doc_id','id');
    }

    public function document_action(){
        return $this->hasMany('App\DocumentAction','document_id','id');
    }
}
