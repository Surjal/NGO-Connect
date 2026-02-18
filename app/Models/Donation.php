<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ngo_id',
        'transaction_uuid',
        'donation_amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ngo()
    {
        return $this->belongsTo(User::class, 'ngo_id');
    }

    public function payments()
    {
        return $this->hasMany(DonationHasPayment::class, 'donation_id');
    }
}
