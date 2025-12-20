<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'address'];

    // Relationship: A Supplier has many Purchases
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}