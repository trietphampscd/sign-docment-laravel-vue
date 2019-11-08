<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class CompanySize extends Model
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'company_sizes';

    protected $fillable = [
        'company_size_name', 'size_from', 'size_to', 'size'
    ];

    protected $hidden = [
    ];

    private $rules = array(
        'company_size_name' => 'required|unique:company_sizes',
        'size_from' => 'required',
        'size_to' => 'required',
    );
}
