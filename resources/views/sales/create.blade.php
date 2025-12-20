@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('sales.store') }}" method="POST">
        @csrf
        
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h4 class="mb-0 fw-bold text-primary">Point of Sale (POS)</h4>
                    </div>
                    <div class="card-body">
                        
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success text-center">
                                <h4 class="alert-heading"><i class="bi bi-check-circle"></i> Sale Complete!</h4>
                                <p>{{ session('success') }}</p>
                                
                                @if(session('change_amount'))
                                    <hr>
                                    <p class="mb-0">Change Due to Customer:</p>
                                    <h1 class="display-3 fw-bold text-dark">
                                        ₵{{ number_format(session('change_amount'), 2) }}
                                    </h1>
                                @endif

                                @if(session('sale_id'))
                                    <div class="mt-3">
                                        <a href="{{ route('sales.print', session('sale_id')) }}" target="_blank" class="btn btn-dark btn-lg shadow">
                                            <i class="bi bi-printer"></i> Print Receipt
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <table class="table table-bordered" id="posTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="45%">Product</th>
                                    <th width="20%">Price (₵)</th>
                                    <th width="15%">Qty</th>
                                    <th width="15%">Total</th>
                                    <th width="5%"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select name="products[]" class="form-select product-select" required>
                                            <option value="" data-price="0">Select Product</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control price-display" readonly>
                                    </td>
                                    <td>
                                        <input type="number" name="quantities[]" class="form-control qty" min="1" value="1" required>
                                    </td>
                                    <td class="row-total fw-bold text-end">0.00</td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm remove-row">X</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <button type="button" class="btn btn-success btn-sm" id="addRow">+ Add Another Item</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white py-3">
                        <h4 class="mb-0 fw-bold">Checkout</h4>
                    </div>
                    <div class="card-body">
                        
                        <div class="d-flex justify-content-between mb-3">
                            <span class="fs-5">Total:</span>
                            <span class="fs-4 fw-bold text-primary">₵<span id="grandTotal">0.00</span></span>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="fw-bold">Payment Method</label>
                            <select name="payment_method" class="form-select">
                                <option value="Cash">Cash</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="fw-bold">Amount Received</label>
                            <input type="number" name="received_amount" class="form-control form-control-lg" required placeholder="0.00">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 btn-lg">Complete Sale</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
{{-- LOAD JQUERY MANUALLY TO BE SAFE --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        console.log("System Ready: Script is running."); // Debug 1

        // 1. Add New Row
        $('#addRow').click(function() {
            var newRow = `<tr>
                            <td>
                                <select name="products[]" class="form-select product-select" required>
                                    <option value="" data-price="0">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}">
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" class="form-control price-display" readonly></td>
                            <td><input type="number" name="quantities[]" class="form-control qty" min="1" value="1" required></td>
                            <td class="row-total fw-bold text-end">0.00</td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                          </tr>`;
            $('#posTable tbody').append(newRow);
        });

        // 2. Remove Row
        $(document).on('click', '.remove-row', function() {
            $(this).closest('tr').remove();
            calculateGrandTotal();
        });

        // 3. Update Price when Product is selected (UPDATED)
        $(document).on('change', '.product-select', function() {
            var selectedOption = $(this).find('option:selected');
            var price = selectedOption.attr('data-price'); 
            
            // --- DEBUGGING ---
            console.log("Product Changed!");
            console.log("Price found in attribute: " + price);
            // ------------------

            var row = $(this).closest('tr');
            var finalPrice = parseFloat(price) || 0;
            
            // Set the value of the input box
            row.find('.price-display').val(finalPrice.toFixed(2));
            
            updateRowTotal(row);
        });

        // 4. Update Total when Qty changes
        $(document).on('input', '.qty', function() {
            var row = $(this).closest('tr');
            updateRowTotal(row);
        });

        // Helper: Calculate Row Total
        function updateRowTotal(row) {
            var price = parseFloat(row.find('.price-display').val()) || 0;
            var qty = parseFloat(row.find('.qty').val()) || 0;
            var total = price * qty;
            row.find('.row-total').text(total.toFixed(2));
            calculateGrandTotal();
        }

        // Helper: Calculate Grand Total
        function calculateGrandTotal() {
            var grandTotal = 0;
            $('.row-total').each(function() {
                grandTotal += parseFloat($(this).text());
            });
            $('#grandTotal').text(grandTotal.toFixed(2));
        }
    });
</script>