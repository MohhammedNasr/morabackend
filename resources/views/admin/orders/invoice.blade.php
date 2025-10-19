<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->reference_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-header { margin-bottom: 2rem; }
        .invoice-header h1 { margin: 0; }
        .invoice-details { margin-bottom: 2rem; }
        .invoice-table { width: 100%; border-collapse: collapse; }
        .invoice-table th, .invoice-table td { 
            border: 1px solid #ddd; 
            padding: 8px;
            text-align: left;
        }
        .invoice-table th { background-color: #f5f5f5; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice #{{ $order->reference_number }}</h1>
        <p>Date: {{ $order->created_at->format('Y-m-d') }}</p>
    </div>

    <div class="invoice-details">
        <h3>Store Details</h3>
        <p>{{ $order->store->name }}</p>
        @if($order->store->users->first())
            <p>Contact: {{ $order->store->users->first()->name }}</p>
        @endif
    </div>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product ? $item->product->name : 'Product Not Found' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->total_price, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" class="text-right">Total</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="invoice-footer" style="margin-top: 2rem;">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
