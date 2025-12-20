<!DOCTYPE html>
<html>
<head>
    <title>Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .date-range { font-size: 14px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .total-box { margin-top: 20px; text-align: right; font-size: 16px; font-weight: bold; }
    </style>
</head>
<body>

    <div class="header">
        <h2>INVENTORY PRO</h2>
        <p>Sales Report</p>
    </div>

    <div class="date-range">
        <strong>Period:</strong> {{ $startDate }} to {{ $endDate }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice</th>
                <th>Cashier</th>
                <th>Payment</th>
                <th style="text-align: right;">Amount (₵)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                <td>{{ $sale->invoice_no }}</td>
                <td>{{ $sale->user->name }}</td>
                <td>{{ $sale->payment_method }}</td>
                <td style="text-align: right;">{{ number_format($sale->total_amount, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-box">
        Total Revenue: ₵{{ number_format($totalAmount, 2) }}
    </div>

</body>
</html>