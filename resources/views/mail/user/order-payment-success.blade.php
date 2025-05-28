<html>

<body>
    <p>We've successfully received your payment for order #{{ $order->order_id }}.</p>

    <p>
        <strong>Amount Paid:</strong> {{ number_format($order->final_amount, 0, ',', '.') }}<br>

        <strong>Payment Date:</strong> {{ $order->payment_date->format('d F Y, H:i T') }}
        We'll notify you once your order is processed by our team.
    </p>

    <p>If you have any questions, contact our support team.</p>

    <p>Thanks,<br>
        Pelita Mesin Jahit</p>
</body>

</html>
