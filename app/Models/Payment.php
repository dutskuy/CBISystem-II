<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'order_id', 'payment_code', 'amount', 'bank_name',
        'account_number', 'account_name', 'transfer_proof',
        'sender_name', 'sender_bank', 'transfer_date',
        'status', 'rejection_reason', 'verified_by', 'verified_at'
    ];

    protected $casts = [
        'transfer_date' => 'datetime',
        'verified_at'   => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}