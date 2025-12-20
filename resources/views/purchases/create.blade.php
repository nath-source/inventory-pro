@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('purchases.store') }}" method="POST">
        @csrf
        
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="mb-0 fw-bold text-primary">Create New Purchase (Stock In)</h4>
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

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="fw-bold">Supplier</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Select Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold">Date</label>
                        <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <h5 class="fw-bold mb-3">Items to Purchase</h5>
                <table class="table table-bordered" id="purchaseTable">
                    <thead class="table-light">
                        <tr>
                            <th width="40%">Product</th>
                            <th width="20%">Quantity</th>
                            <th width="20%">Unit Cost (₵)</th>
                            <th width="10%">Total</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="products[]" class="form-select" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} (Cur: {{ $product->stock }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" name="quantities[]" class="form-control qty" min="1" value="1" required>
                            </td>
                            <td>
                                <input type="number" name="prices[]" class="form-control price" step="0.01" min="0" required>
                            </td>
                            <td class="row-total fw-bold text-end">0.00</td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <button type="button" class="btn btn-success btn-sm mb-3" id="addRow">+ Add Another Item</button>

                <div class="d-flex justify-content-end align-items-center gap-3">
                    <h4 class="mb-0">Grand Total: <span class="text-primary">₵<span id="grandTotal">0.00</span></span></h4>
                    <button type="submit" class="btn btn-primary btn-lg px-5">Save Purchase</button>
                </div>

            </div>
        </div>
    </form>
</div>

<script type="module">
    $(document).ready(function() {
        
        // 1. Function to Add a New Row
        $('#addRow').click(function() {
            var newRow = `<tr>
                            <td>
                                <select name="products[]" class="form-select" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="quantities[]" class="form-control qty" min="1" value="1" required></td>
                            <td><input type="number" name="prices[]" class="form-control price" step="0.01" min="0" required></td>
                            <td class="row-total fw-bold text-end">0.00</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                          </tr>`;
            $('#purchaseTable tbody').append(newRow);
        });

        // 2. Function to Remove a Row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateTotal(); // Recalculate grand total
        });

        // 3. Function to Calculate Totals when typing
        $(document).on('input', '.qty, .price', function() {
            var row = $(this).closest('tr');
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var price = parseFloat(row.find('.price').val()) || 0;
            var total = qty * price;
            
            row.find('.row-total').text(total.toFixed(2));
            calculateTotal();
        });

        // 4. Calculate Grand Total
        function calculateTotal() {
            var grandTotal = 0;
            $('.row-total').each(function() {
                grandTotal += parseFloat($(this).text());
            });
            $('#grandTotal').text(grandTotal.toFixed(2));
        }
    });
</script>
@endsection