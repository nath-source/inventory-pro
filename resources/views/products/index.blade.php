@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-primary">Product Management</h4>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        + Add New Product
                    </a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>SKU (Barcode)</th>
                                <th>Cost Price</th>
                                <th>Selling Price</th>
                                <th>Stock</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                       <tbody>
                            @forelse ($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-bold">{{ $product->name }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $product->category->name ?? 'No Category' }}</span>
                                </td>
                                <td class="font-monospace">{{ $product->sku }}</td>
                                <td>₵{{ number_format($product->cost_price, 2) }}</td>
                                <td>₵{{ number_format($product->selling_price, 2) }}</td>
                                <td>
                                    @if($product->stock <= $product->reorder_level)
                                        <span class="badge bg-danger">{{ $product->stock }} (Low)</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">
                                            Edit
                                        </a>

                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $product->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">
                                    No products found. Click "Add New Product" to begin.
                                </td>
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