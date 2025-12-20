<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'invoice_no', 'date', 'total_amount'];

    // A purchase belongs to a supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // A purchase has many items inside it
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}