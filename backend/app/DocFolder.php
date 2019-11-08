<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class DocFolder extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'doc_folders';

    protected $fillable = [
        'doc_folder_id', 'doc_folder_name', 'user_id', 'priority', 'description', 'parent_id'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'doc_folder_id' => 'required|unique:templates',
        'doc_folder_name' => 'required|unique:templates',
        'user_id' => 'required'
    );

    public function parent()
    {
        return $this->belongsTo('App\DocFolder','parent_id')->where('parent_id',0)->with('parent');
    }

    public function children()
    {
        return $this->hasMany('App\DocFolder','parent_id')->with('children');
    }
}
