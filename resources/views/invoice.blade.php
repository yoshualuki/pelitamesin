<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.4;
        }

        .header {
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
        }

        .company-info {
            float: left;
        }

        .invoice-info {
            float: right;
            text-align: right;
        }

        .clear {
            clear: both;
        }

        .customer-info {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th,
        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background: #f5f5f5;
            text-align: left;
        }

        .total-info {
            float: right;
            width: 300px;
            margin: 20px 0;
        }

        .total-info table {
            margin: 0;
        }

        .footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-info">
            <h2>Pelita Mesin</h2>
            <p>
                Jl. Bubutan No.101A, Surabaya<br>
                Phone: (031) 99250845<br>
                Email: pelita.sewing@gmail.com
            </p>
        </div>
        <div class="invoice-info">
            <h2>INVOICE</h2>
            <p>
                No: #{{ $order->order_id }}<br>
                Tanggal: {{ $order->created_at->format('d/m/Y') }}<br>
                Status: {{ $order->status }}
            </p>
        </div>
        <div class="clear"></div>
    </div>

    <div class="customer-info">
        <h3>Informasi Pelanggan:</h3>
        <p>
            {{ $order->recipient_name }}<br>
            {{ $order->shipping_address }}<br>
            {{ $order->city }}, {{ $order->province }}<br>
            Phone: {{ $order->recipient_phone }}
        </p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th style="text-align: right">Harga</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: center">{{ $item->quantity }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-info">
        <table>
            <tr>
                <th>Subtotal:</th>
                <td style="text-align: right">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Ongkir ({{ strtoupper($order->courier) }}):</th>
                <td style="text-align: right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total:</th>
                <td style="text-align: right">Rp {{ number_format($order->final_amount, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="clear"></div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di Pelita Mesin</p>
    </div>
</body>

</html>
