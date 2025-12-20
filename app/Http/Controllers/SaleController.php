<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // <--- Import this!

class SaleController extends Controller
{
    // 1. Show the POS Interface
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    // 2. Process the Checkout
    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'quantities' => 'required|array',
            'received_amount' => 'required|numeric',
            'payment_method' => 'required|string',
        ]);

        // Transaction
        $sale = DB::transaction(function () use ($request) {
            
            // Calculate Total
            $totalAmount = 0;
            foreach ($request->products as $key => $productId) {
                $product = Product::find($productId);
                $qty = $request->quantities[$key];
                $totalAmount += ($product->selling_price * $qty);
            }

            // Create Sale
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'invoice_no' => 'POS-' . time(),
                'total_amount' => $totalAmount,
                'received_amount' => $request->received_amount,
                'payment_method' => $request->payment_method,
            ]);

            // Save Items & Reduce Stock
            foreach ($request->products as $key => $productId) {
                $product = Product::find($productId);
                $qty = $request->quantities[$key];

                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $qty,
                    'price' => $product->selling_price,
                ]);

                $product->decrement('stock', $qty);
            }

            return $sale; 
        });

        // Calculate Change
        $change = $sale->received_amount - $sale->total_amount;

        return redirect()->back()
            ->with('success', 'Sale completed successfully!')
            ->with('change_amount', $change)
            ->with('sale_id', $sale->id);
    }

    // 3. Print the Receipt (Renamed to showReceipt to avoid errors)
    public function showReceipt($id)
    {
        $sale = Sale::with(['user', 'items.product'])->findOrFail($id);
        return view('sales.print', compact('sale'));
    }
    // 4. Sales Report with Date Filter
    public function report(Request $request)
    {
        // Default to Today if no date selected
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');

        // Fetch sales between these dates
        $sales = Sale::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        // Calculate total for this period
        $totalAmount = $sales->sum('total_amount');

        return view('reports.index', compact('sales', 'startDate', 'endDate', 'totalAmount'));
    }
    // 5. Export Report to PDF
    public function exportPdf(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-d');
        $endDate = $request->end_date ?? date('Y-m-d');

        $sales = Sale::with('user')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->latest()
            ->get();

        $totalAmount = $sales->sum('total_amount');

        // Load the specific PDF view
        $pdf = Pdf::loadView('reports.pdf', compact('sales', 'startDate', 'endDate', 'totalAmount'));

        // Download the file
        return $pdf->download('sales_report.pdf');
    }
}