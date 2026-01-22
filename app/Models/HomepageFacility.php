<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageFacility extends Model
{
    protected $table = 'homepage_facilities';
    protected $fillable = ['category', 'name', 'icon', 'is_visible', 'order'];
}
