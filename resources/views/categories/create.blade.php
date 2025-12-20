@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h4 class="mb-0 fw-bold text-primary">Add New Category</h4>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">Category Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Beverages">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection