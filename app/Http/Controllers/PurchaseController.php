<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // We use DB Transaction for safety

class PurchaseController extends Controller
{
    // 1. Show the list of all purchases
    public function index()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        return view('purchases.index', compact('purchases'));
    }

    // 2. Show the form to create a new purchase
    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all(); // We need products to select what we are buying
        return view('purchases.create', compact('suppliers', 'products'));
    }

    // 3. Store the Purchase AND Increase Stock
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required',
            'date' => 'required|date',
            // We expect an array of items (product_id[], quantity[], cost[])
            'products' => 'required|array',
            'quantities' => 'required|array',
            'prices' => 'required|array',
        ]);

        // DB Transaction: If anything fails, it undoes everything (Safety First)
        DB::transaction(function () use ($request) {
            // A. Create the Main Purchase Record
            $purchase = Purchase::create([
                'supplier_id' => $request->supplier_id,
                'invoice_no' => 'INV-' . time(), // Auto-generate invoice number
                'date' => $request->date,
                'total_amount' => 0, // We will calculate this below
            ]);

            $totalAmount = 0;

            // B. Loop through the items
            foreach ($request->products as $key => $productId) {
                $qty = $request->quantities[$key];
                $cost = $request->prices[$key];
                $totalAmount += ($qty * $cost);

                // C. Save the Purchase Item
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'cost_price' => $cost,
                ]);

                // D. CRITICAL: Increase the Product Stock 
                $product = Product::find($productId);
                $product->increment('stock', $qty);
            }

            // E. Update the total amount
            $purchase->update(['total_amount' => $totalAmount]);
        });

        return redirect()->route('purchases.index')->with('success', 'Stock updated successfully!');
    }
    // 4. Show a single Purchase Invoice
    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.product'])->findOrFail($id);
        return view('purchases.show', compact('purchase'));
    }
}