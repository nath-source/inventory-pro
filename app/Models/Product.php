<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'sku', 
        'description',
        'category_id', 
        'cost_price', 
        'selling_price', 
        'stock', 
        'reorder_level', 
        'image'
    ];

    // Relationship: A Product belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}