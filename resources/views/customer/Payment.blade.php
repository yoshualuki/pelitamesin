<div class="container mt-5">
    <h1>Payment</h1>
    <form action="{{ route('payment.process') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
            <label for="phone">Phone</label>
            <input type="text" class="form-control" id="phone" name="phone" required>
            <label for="address">Address</label>
            <input type="text" class="form-control" id="address" name="address" required>
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required>
            <label for="state">State</label>
            <input type="text" class="form-control" id="state" name="state" required>
            <label for="zip">Zip</label>
            <input type="text" class="form-control" id="zip" name="zip" required>
            <label for="country">Country</label>
            <input type="text" class="form-control" id="country" name="country" required>
            <label for="card_number">Card Number</label>
            <input type="text" class="form-control" id="card_number" name="card_number" required>
            <label for="expiry_date">Expiry Date</label>
            <input type="text" class="form-control" id="expiry_date" name="expiry_date" required>
            <label for="cvv">CVV</label>
            <input type="text" class="form-control" id="cvv" name="cvv" required>
            <button type="submit" class="btn btn-primary">Pay</button>
            <a href="{{ route('products') }}" class="btn btn-secondary">Cancel</a>

        </div>
    </form>
</div>



@endsection

