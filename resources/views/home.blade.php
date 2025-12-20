@extends('layouts.app')

@section('content')
<div class="container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark">Dashboard Overview</h2>
        <p class="text-muted">{{ date('l, d F Y') }}</p>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2 opacity-75">Sales Today</h6>
                    <h2 class="fw-bold mb-0">₵{{ number_format($todaySales, 2) }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2 text-muted">Total Products</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 bg-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase mb-2 text-muted">Categories</h6>
                    <h2 class="fw-bold mb-0 text-dark">{{ $totalCategories }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-graph-up"></i> Sales Performance (Last 7 Days)
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="120"></canvas>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-pie-chart-fill"></i> Inventory Mix
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alerts</h5>
                </div>
                <div class="card-body">
                    @if($lowStockItems->count() > 0)
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Alert Level</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockItems as $item)
                                <tr class="table-danger"> <td class="fw-bold">{{ $item->name }}</td>
                                    <td>{{ $item->category->name ?? 'None' }}</td>
                                    <td class="text-danger fw-bold">{{ $item->stock }}</td>
                                    <td>{{ $item->reorder_level }}</td>
                                    <td>
                                        <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-dark">
                                            Restock Now
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4 text-success">
                            <i class="bi bi-check-circle-fill fs-1"></i>
                            <p class="mt-2 fw-bold">All stock levels are healthy!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <br>
    <br>
    <div class="row mb-5">
        
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-success">
                        <i class="bi bi-trophy-fill"></i> Best Selling Products
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-end">Units Sold</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bestSellers as $item)
                            <tr>
                                <td class="fw-bold">{{ $item->name }}</td>
                                <td class="text-end">{{ $item->total_sold }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">No sales yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <br>
        <div class="col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-info">
                        <i class="bi bi-clock-history"></i> Recent Transactions
                    </h5>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Cashier</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentSales as $sale)
                            <tr>
                                <td class="font-monospace text-primary">{{ $sale->invoice_no }}</td>
                                <td class="small">{{ $sale->user->name }}</td>
                                <td class="text-end fw-bold">₵{{ number_format($sale->total_amount, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Data from Controller
        const labels = @json($dates);
        const data = @json($totals);

        new Chart(ctx, {
            type: 'bar', // You can change this to 'line' if you prefer
            data: {
                labels: labels,
                datasets: [{
                    label: 'Daily Sales (₵)',
                    data: data,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)', // Blue bars
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₵' + value; // Add Cedi symbol
                            }
                        }
                    }
                }
            }
        });
        // --- NEW: Category Pie Chart ---
        const ctx2 = document.getElementById('categoryChart').getContext('2d');
        const catLabels = @json($catNames);
        const catData = @json($catCounts);

        new Chart(ctx2, {
            type: 'doughnut', // 'pie' or 'doughnut'
            data: {
                labels: catLabels,
                datasets: [{
                    data: catData,
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    });
</script>