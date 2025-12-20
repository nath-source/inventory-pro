@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            <h2 class="fw-bold text-primary mb-4">Sales Report</h2>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body">
                    <form action="{{ route('reports.index') }}" method="GET" class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search"></i> Filter
                        </button>

                        <a href="{{ route('reports.pdf', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn btn-danger">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">Sales History</h5>
                    <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-printer"></i> Print Report
                    </button>
                </div>
                <div class="card-body">
                    
                    <div class="alert alert-info text-center fw-bold">
                        Total Sales for Period: ₵{{ number_format($totalAmount, 2) }}
                    </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Date</th>
                                <th>Invoice</th>
                                <th>Cashier</th>
                                <th>Payment</th>
                                <th class="text-end">Amount (₵)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sales as $sale)
                            <tr>
                                <td>{{ $sale->created_at->format('d M Y, h:i A') }}</td>
                                <td class="font-monospace">{{ $sale->invoice_no }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td>{{ $sale->payment_method }}</td>
                                <td class="text-end fw-bold">{{ number_format($sale->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('sales.print', $sale->id) }}" target="_blank" class="btn btn-sm btn-outline-dark">
                                        Receipt
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No sales found for this date range.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection