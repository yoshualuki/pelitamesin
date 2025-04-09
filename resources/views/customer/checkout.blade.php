@extends('customer.template')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Checkout</h2>
    
    <div class="row">
        <!-- Products Column -->
        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Your Products</h5>
                </div>
                <div class="card-body">
                    @foreach ($products as $product)
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-3">
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}"
                                 class="img-fluid rounded" style="max-height: 100px; width: auto;">
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-1">{{ $product->name }}</h6>
                            <p class="mb-1 text-muted">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="mb-0">Quantity: {{ $product->quantity }}</p>
                        </div>
                        <div class="col-md-3 text-end">
                            <p class="mb-0 fw-bold">Rp {{ number_format($product->price * $product->quantity, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="row mt-3">
                        <div class="col-8">
                            <h5>Berat</h5>
                        </div>
                        <div class="col-4 text-end">
                            <h5>{{ number_format($totalWeight/1000, 0, ',', '.') }} Kg</h5>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-8">
                            <h5>Subtotal</h5>
                        </div>
                        <div class="col-4 text-end">
                            <h5>Rp {{ number_format($subtotal, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Form Column -->
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <form id="checkoutForm">
                        @csrf
                        <input type="hidden" id="shipping_cost" name="shipping_cost" value="0">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Recipient Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <div class="invalid-feedback">Please enter recipient name</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">Please enter a valid email</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                            <div class="invalid-feedback">Please enter phone number</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                            <div class="invalid-feedback">Please enter your address</div>
                        </div>
                        
                        <!-- Dynamic Location Selection -->
                        <div class="mb-3">
                            <label for="province" class="form-label">Province <span class="text-danger">*</span></label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="">Select Province</option>
                                @foreach ($provinces as $p)
                                <option value="{{ $p['province_id'] }}">{{ $p['province'] }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">Please select province</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="city" class="form-label">City <span class="text-danger">*</span></label>
                            <select class="form-select" id="city" name="city" disabled required>
                                <option value="">Select City</option>
                            </select>
                            <div class="invalid-feedback">Please select city</div>
                        </div>

                        <div class="mb-3">
                            <label for="courier" class="form-label">Courier <span class="text-danger">*</span></label>
                            <select class="form-select" id="courier" name="courier" required>
                                <option value="">Select Courier</option>
                                <option value="jne">JNE</option>
                                <option value="pos">POS</option>
                            </select>
                            <div class="invalid-feedback">Please select courier</div>
                        </div>
                        

                        <div class="mb-3">
                            <label for="service" class="form-label">Service <span class="text-danger">*</span></label>
                            <select class="form-select" id="service" name="service" disabled required>
                                <option value="">Select Service</option>
                            </select>
                            <div class="invalid-feedback">Please select shipping service</div>
                        </div>
                        
                        <!-- Shipping Cost Display -->
                        <div class="alert alert-info mb-3 d-none" id="shippingCostAlert">
                            <div>Shipping Cost: <span id="shippingCost">Rp 0</span></div>
                            <div>Estimated Delivery: <span id="estimatedDelivery">-</span> days</div>
                        </div>
                        
                        <!-- Total Payment -->
                        <div class="border-top pt-3 mt-3">
                            <div class="d-flex justify-content-between fw-bold fs-5">
                                <span>Total Payment:</span>
                                <span id="totalPayment">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button id="pay-button" type="button" class="btn btn-primary">Proceed to Payment</button>
                            <a href="{{ route('cart') }}" class="btn btn-outline-secondary">Back to Cart</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    
    $('#name').val(`{{ session()->get('user')->name }}`);
    $('#email').val(`{{ session()->get('user')->email }}`);
    $('#phone').val(`{{ session()->get('user')->phone }}`);
    $('#address').val(`{{ session()->get('user')->address }}`);
    

    // Form validation
    function validateForm() {
        let isValid = true;
        const requiredFields = [
            '#name', '#email', '#phone', '#address', 
            '#province', '#city', '#courier', '#service'
        ];
        
        requiredFields.forEach(field => {
            const element = $(field);
            if (!element.val()) {
                element.addClass('is-invalid');
                isValid = false;
            } else {
                element.removeClass('is-invalid');
            }
        });
        
        // Additional validation for email format
        const email = $('#email').val();
        if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $('#email').addClass('is-invalid');
            isValid = false;
        }
        const phone = $('#phone').val();
        if (phone && !/^[0-9]{10,13}$/.test(phone)) {
            $('#phone').addClass('is-invalid');
            isValid = false;
        }

        return isValid;
    }

    // Input field validation on change
    $('input, select').on('change', function() {
        if ($(this).val()) {
            $(this).removeClass('is-invalid');
        }
    });

    // Province change event
    $('#province').on('change', function() {
        $('#city').prop('disabled', true);
        $('#service').prop('disabled', true);
        resetShippingCost();

        const provinceId = $(this).val();
        
        $('#city').html('<option value="">Loading...</option>');
        
        if (provinceId) {
            $.get(`/cart/cities?province_id=${provinceId}`, function(data) {
                let options = '<option value="">Select City</option>';
                data.data.forEach(city => {
                    options += `<option value="${city.city_id}">${city.city_name}</option>`;
                });
                $('#city').html(options).prop('disabled', false);
            }).fail(function() {
                $('#city').html('<option value="">Error loading cities</option>');
            });
        }
    });

    // City change event
    $('#city').on('change', function() {
        $('#service').prop('disabled', true);
        resetShippingCost();
    });

    // Courier change event
    $('#courier').on('change', function() {
        $('#service').prop('disabled', true);
        resetShippingCost();
    });

    // City and Courier change - calculate shipping
    $('#city, #courier').on('change', function() {
        if ($('#city').val() && $('#courier').val()) {
            calculateShippingCost();
        }
    });

    // Service change event
    $('#service').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const shippingCost = selectedOption.data('cost');
        const estimatedDays = selectedOption.data('etd');
        
        if (shippingCost) {
            // Update shipping cost display
            $('#shippingCost').text('Rp ' + shippingCost.toLocaleString('id-ID'));
            $('#shippingCostAlert').removeClass('d-none');
            $('#estimatedDelivery').text(estimatedDays);
            
            // Update hidden field and total payment
            $('#shipping_cost').val(shippingCost);
            const subtotal = {{ $subtotal }};
            const total = subtotal + parseInt(shippingCost);
            $('#totalPayment').text('Rp ' + total.toLocaleString('id-ID'));
        } else {
            resetShippingCost();
        }
    });

    function calculateShippingCost() {
        const cityId = $('#city').val();
        const courier = $('#courier').val();
        
        $('#service').html('<option value="">Loading...</option>');
        
        $.get(`/cart/shipping-cost`, {
            destination: cityId,
            courier: courier,
            weight: {{ $totalWeight }}
        }, function(response) {
            if(response.success) {
                let options = '<option value="">Select Service</option>';
                response.services.forEach(cost => {
                    options += `
                    <option 
                        value="${cost.service}" 
                        data-cost="${cost.cost[0].value}" 
                        data-etd="${cost.cost[0].etd}">
                        ${cost.service} - Rp ${cost.cost[0].value.toLocaleString('id-ID')} (${cost.cost[0].etd} days)
                    </option>`;
                });
                $('#service').html(options).prop('disabled', false);
            }
        }).fail(function() {
            $('#service').html('<option value="">Error loading services</option>');
            resetShippingCost();
        });
    }

    function resetShippingCost() {
        $('#shippingCost').text('Rp 0');
        $('#estimatedDelivery').text('-');
        $('#shippingCostAlert').addClass('d-none');
        $('#shipping_cost').val(0);
        $('#totalPayment').text('Rp {{ number_format($subtotal, 0, ',', '.') }}');
    }

    // Payment button handler
    $("#pay-button").on('click', function(e) {
        e.preventDefault();
        
        if (!validateForm()) {
            // Scroll to first invalid field
            $('html, body').animate({
                scrollTop: $('.is-invalid').first().offset().top - 100
            }, 500);
            return;
        }

        // Check if shipping service is selected
        if (!$('#service').val()) {
            $('#service').addClass('is-invalid');
            $('html, body').animate({
                scrollTop: $('#service').offset().top - 100
            }, 500);
            return;
        }

        // Disable button to prevent multiple clicks
        $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
        
        // Collect form data
        const formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val(),
            province: $('#province option:selected').text(),
            city: $('#city option:selected').text(),
            courier: $('#courier').val(),
            service: $('#service').val(),
            estimated_delivery: $('#estimatedDelivery').val(),
            shipping_cost: $('#shipping_cost').val(),
            _token: $('input[name="_token"]').val()
        };

        // First update customer data
        $.ajax({
            url: '/checkout/process-payment',
            method: 'POST',
            data: formData,
            success: function(response) {
                window.snap.pay(response.snapToken, {
                    onSuccess: function(result) {
                        window.location.href = '/orders/' + response.orderId;
                    },
                    onPending: function(result) {
                        window.location.href = '/orders/' + response.orderId;
                    },
                    onError: function(result) {
                        showSwalError('Payment failed: ' + result.status_message);
                        $('#pay-button').prop('disabled', false).text('Proceed to Payment');
                    },
                    onClose: function() {
                        $('#pay-button').prop('disabled', false).text('Proceed to Payment');
                    }
                });
            },
            error: function(xhr) {
                $('#pay-button').prop('disabled', false).text('Proceed to Payment');
                showSwalError('Failed to update customer information: ' + xhr.responseJSON.message);
            }
        });
    });

});
</script>
@endsection