<?php

namespace App\Models;

use App\Traits\UUID;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventParticipant extends Model
{
    use SoftDeletes, UUID, HasFactory;

    protected $fillable = [
        'event_id',
        'head_of_family_id',
        'quantity',
        'total_price',
        'payment_status',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'total_price' => 'decimal:2'
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function headOfFamily()
    {
        return $this->belongsTo(HeadOfFamily::class);
    }
}
