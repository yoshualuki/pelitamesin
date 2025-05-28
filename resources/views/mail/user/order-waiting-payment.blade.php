<html>

<body>
    <p>Order Waiting for Payment - Order #{{ $order->order_id }}</p>

    <p>Hello {{ $user->name }},</p>


    <p>Your order has been created successfully. Please complete your payment to process your order.</p>

    <p>
        <strong>Order Total:</strong> {{ $order->final_amount }}<br>

        <strong>Payment Due:</strong> {{ now()->addHours(24)->format('d F Y, H:i T') }}
    </p>

    <p>If you have any questions, contact our support team.</p>

    <p>Thanks,<br>
        Pelita Mesin Jahit</p>
</body>

</html>
