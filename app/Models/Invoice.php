<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'order_id', 'user_id',
        'subtotal', 'tax', 'total',
        'issued_date', 'due_date', 'status', 'pdf_path'
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date'    => 'date',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}