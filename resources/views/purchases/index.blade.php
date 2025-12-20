@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-primary">Purchase History</h4>
            <a href="{{ route('purchases.create') }}" class="btn btn-primary">
                + New Purchase
            </a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Invoice No</th>
                        <th>Supplier</th>
                        <th>Total Amount</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->date }}</td>
                        <td class="font-monospace">{{ $purchase->invoice_no }}</td>
                        <td>{{ $purchase->supplier->name ?? 'Unknown' }}</td>
                        <td class="fw-bold">₵{{ number_format($purchase->total_amount, 2) }}</td>
                        <td>
                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-eye"></i> View Details
                        </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No purchases found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>    
        </div>
    </div>
</div>
@endsection