<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WebHook extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'shortcode', 'link', 'username', 'password', 'key', 'secret'
    ];
}
