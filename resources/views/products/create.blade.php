@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold text-primary">Add New Product</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    
                    <form action="{{ route('products.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. iPhone 13">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">SKU (Barcode)</label>
                                <input type="text" name="sku" class="form-control" required placeholder="Scan or type code">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Initial Stock</label>
                                <input type="number" name="stock" class="form-control" value="0" min="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Alert Level</label>
                                <input type="number" name="reorder_level" class="form-control" value="10">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cost Price (Buying)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₵</span>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Selling Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₵</span>
                                    <input type="number" name="selling_price" class="form-control" step="0.01" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Save Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection