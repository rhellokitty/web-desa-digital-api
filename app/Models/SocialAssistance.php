<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistance extends Model
{
    use SoftDeletes, UUID;

    protected $fillable = [
        'thumbnail',
        'name',
        'category',
        'amount',
        'provider',
        'description',
        'is_available',
    ];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'Like', '%' . $search . '%')
            ->orWhere('provider', 'Like', '%' . $search . '%')
            ->orWhere('amount', 'Like', '%' . $search . '%');
    }

    public function socialAsisstanceRecipients()
    {
        return $this->hasMany(SocialAssistanceRecipient::class);
    }
}
