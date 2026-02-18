<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationHasPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_id',
        'payment_method',
        'payment_response',
        'status',
    ];


    protected $casts = [
        'payment_response' => 'array',
    ];

    public function donation()
    {
        return $this->belongsTo(Donation::class);
    }
}
