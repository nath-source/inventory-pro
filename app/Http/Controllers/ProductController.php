<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. List all products
    public function index()
    {
        // Get products with their category info
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    // 2. Show form to create a new product
    public function create()
    {
        $categories = Category::all(); // We need this for the dropdown
        return view('products.create', compact('categories'));
    }

    // 3. Save the product
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'sku' => 'required|unique:products',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Product created successfully.');
    }
    // 4. Show the Edit Form
    public function edit(Product $product)
    {
        $categories = Category::all(); // We need categories for the dropdown
        return view('products.edit', compact('product', 'categories'));
    }

    // 5. Update the Product in Database
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            // Unique SKU check, but ignore the current product's own ID
            'sku' => 'required|unique:products,sku,' . $product->id,
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Product updated successfully.');
    }

    // 6. Delete the Product
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
                         ->with('success', 'Product deleted successfully.');
    }
}