<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomepageSetting extends Model
{
    protected $table = 'homepage_settings';
    protected $fillable = ['description', 'slider_images'];
    protected $casts = [
        'slider_images' => 'array',
    ];
}
