<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Import Models
use App\Models\Sale;
use App\Models\Category;
use Carbon\Carbon; // Needed for date filtering

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // ... (Keep existing code: totalProducts, todaySales, Charts data, etc.) ...
        $totalProducts = Product::count();
        $todaySales = Sale::whereDate('created_at', Carbon::today())->sum('total_amount');
        $lowStockItems = Product::whereColumn('stock', '<=', 'reorder_level')->get();
        $totalCategories = Category::count();
        
        // Sales Chart
        $salesData = Sale::selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        $dates = $salesData->pluck('date');
        $totals = $salesData->pluck('total');

        // Pie Chart
        $categoryData = Category::withCount('products')->get();
        $catNames = $categoryData->pluck('name');
        $catCounts = $categoryData->pluck('products_count');

        // --- NEW: Top 5 Best Selling Products ---
        // We join with sale_items, sum the quantity, and take top 5
        $bestSellers = Product::select('products.name', \DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        // --- NEW: Recent 5 Sales ---
        $recentSales = Sale::with('user')->latest()->take(5)->get();

        return view('home', compact(
            'totalProducts', 'todaySales', 'lowStockItems', 'totalCategories', 
            'dates', 'totals', 
            'catNames', 'catCounts',
            'bestSellers', 'recentSales' // <--- Pass new variables
        ));
    }
}