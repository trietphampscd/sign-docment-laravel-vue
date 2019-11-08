<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Annotation extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'annotations';

    protected $fillable = [
        'annotation_id', 'creator_id', 'actor_id', 'doc_id', 'type', 'page_num', 'pos_x', 'pos_y', 'size_w', 'size_h', 'value', 'z_order', 'alpha', 'text', 'font_family',
        'font_size', 'font_weight', 'font_color', 'image_url'
    ];

    protected $hidden = [
    ];

    public $rules = array(
        // 'annotation_id' => 'required',
        // 'creator_id' => 'required',
        'actor_id' => 'required',
        'doc_id' => 'required',
        // 'type' => 'required',
        'page_num' => 'required',
        'pos_x' => 'required',
        'pos_y' => 'required',
        'size_w' => 'required',
        'size_h' => 'required',
        'alpha' => 'required',
    );
}
