<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SocialAssistanceRecipient extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'social_assistance_id',
        'head_of_family_id',
        'amount',
        'reason',
        'bank',
        'account_number',
        'proof',
        'status'
    ];

    public function scopeSearch($query, $search)
    {
        return $query->whereHas('headOfFamily.user', function ($query) use ($search) {
            $query->where('name', 'Like', '%' . $search . '%');
            $query->orWhere('email', 'Like', '%' . $search . '%');
        })
            ->orWhereHas('socialAssistance', function ($query) use ($search) {
                $query->where('name', 'Like', '%' . $search . '%');
            })
        ;
    }

    public function socialAssistance()
    {
        return $this->belongsTo(SocialAssistance::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class)->withTrashed();
    }
}
