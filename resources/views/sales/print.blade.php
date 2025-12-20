<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->invoice_no }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            background-color: #f8f9fa; /* Grey background for screen */
            padding: 20px;
        }
        .ticket {
            width: 300px; /* Standard Thermal Printer Width */
            margin: 0 auto;
            background-color: #fff; /* White paper */
            padding: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .border-top { border-top: 1px dashed #000; margin: 10px 0; }
        .border-bottom { border-bottom: 1px dashed #000; margin: 10px 0; }
        
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 5px 0; vertical-align: top; }
        
        /* Hides the print button when printing on paper */
        @media print {
            .no-print { display: none; }
            body { background-color: #fff; padding: 0; }
            .ticket { width: 100%; box-shadow: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="ticket">
        <div class="text-center">
            <h3 class="fw-bold mb-1">INVENTORY PRO</h3>
            <p class="mb-1">Accra, Ghana</p>
            <p>Tel: +233 24 000 0000</p>
        </div>

        <div class="border-bottom"></div>

        <div>
            <p class="mb-1"><strong>Invoice:</strong> {{ $sale->invoice_no }}</p>
            <p class="mb-1"><strong>Date:</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
            <p class="mb-1"><strong>Cashier:</strong> {{ $sale->user->name }}</p>
        </div>

        <div class="border-bottom"></div>

        <table>
            <thead>
                <tr style="text-align: left;">
                    <th>Item</th>
                    <th class="text-end">Qty</th>
                    <th class="text-end">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">{{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="border-top"></div>

        <table>
            <tr>
                <td><strong>TOTAL:</strong></td>
                <td class="text-end fw-bold">₵{{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Cash:</td>
                <td class="text-end">₵{{ number_format($sale->received_amount, 2) }}</td>
            </tr>
            <tr>
                <td>Change:</td>
                <td class="text-end">₵{{ number_format($sale->received_amount - $sale->total_amount, 2) }}</td>
            </tr>
        </table>

        <div class="border-bottom"></div>

        <div class="text-center">
            <p class="mb-1">Thank you for shopping!</p>
            <p style="font-size: 12px;">Software by Ademola</p>
        </div>

        <div class="text-center no-print" style="margin-top: 20px;">
            <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; background: black; color: white; border: none;">Print Receipt</button>
            <br><br>
            <a href="{{ route('sales.create') }}" style="color: blue; text-decoration: none;">&larr; Back to POS</a>
        </div>
    </div>

    <script>
        // Automatically open print dialog when page loads
        window.onload = function() {
            window.print();
        };
    </script>

</body>
</html>