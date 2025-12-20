@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold text-primary">Edit Product: {{ $product->name }}</h4>
                </div>

                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Product Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">SKU (Barcode)</label>
                                <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Current Stock</label>
                                <input type="number" name="stock" class="form-control" value="{{ $product->stock }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Alert Level</label>
                                <input type="number" name="reorder_level" class="form-control" value="{{ $product->reorder_level }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Cost Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₵</span>
                                    <input type="number" name="cost_price" class="form-control" step="0.01" value="{{ $product->cost_price }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Selling Price</label>
                                <div class="input-group">
                                    <span class="input-group-text">₵</span>
                                    <input type="number" name="selling_price" class="form-control" step="0.01" value="{{ $product->selling_price }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection