<html>

<body>
    <p>New Order Received - Order #{{ $order->order_id }}</p>

    <p>A new order has been placed and requires processing.</br>
        <strong>Total Amount:</strong> {{ number_format($order->final_amount, 0, ',', '.') }}</br>
        <strong>Items:</strong> {{ $order->items->count() }} products</br>
    </p>
    Please process the order within 24 hours.</br></br>

    Thanks,<br>
    Pelita Mesin Jahit

</body>

</html>
