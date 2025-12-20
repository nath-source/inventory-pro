@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-primary">Invoice #{{ $purchase->invoice_no }}</h4>
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5 class="fw-bold">Supplier Details:</h5>
                    <p class="mb-0">{{ $purchase->supplier->name }}</p>
                    <p class="mb-0">{{ $purchase->supplier->phone }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <h5 class="fw-bold">Date:</h5>
                    <p>{{ $purchase->date }}</p>
                </div>
            </div>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th class="text-end">Quantity</th>
                        <th class="text-end">Unit Cost</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td class="text-end">{{ $item->quantity }}</td>
                        <td class="text-end">₵{{ number_format($item->cost_price, 2) }}</td>
                        <td class="text-end fw-bold">₵{{ number_format($item->quantity * $item->cost_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Grand Total:</td>
                        <td class="text-end fw-bold text-primary">₵{{ number_format($purchase->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection