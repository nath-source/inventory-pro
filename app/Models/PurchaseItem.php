<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = ['purchase_id', 'product_id', 'quantity', 'cost_price'];

    // Each item links back to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}