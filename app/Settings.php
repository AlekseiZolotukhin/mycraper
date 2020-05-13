<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = ['user_id', 'website', 'url', 'selectors', 'counter'];
}
