<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'about',
        'headman',
        'people',
        'agriculutral_area',
        'total_area',
    ];

    protected  $casts = [
        'agriculutral_area' => 'float',
        'total_area' => 'float',
    ];

    public function profileImages()
    {
        return $this->hasMany(ProfileImage::class);
    }
}
