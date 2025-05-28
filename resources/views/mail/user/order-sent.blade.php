<html>

<body>
    <p>Order Sent - Order #{{ $order->order_id }} </p>

    <p>
        Your order has been sent by our team.</br>

        <strong>Estimated Delivery:</strong> {{ $order->estimated_delivery }}</br>
        <strong>Resi Courier {{ $order->courier }}:</strong> {{ $order->tracking_number }}</br>

        Track your order status anytime from your account.
    </p>

    Thanks,<br>
    Pelita Mesin Jahit

</body>

</html>
