<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',        // The Cashier
        'customer_id',    // The Customer (Optional)
        'invoice_no',     // Unique Receipt Number
        'total_amount',   // Final Total
        'received_amount',// How much cash given
        'payment_method'  // Cash, Card, etc.
    ];

    // Relationship: A sale belongs to a User (Cashier)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship: A sale has many items
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }
}