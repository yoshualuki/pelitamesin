@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-3">Refund Inventory</h1>

        <div class="card shadow mb-4">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <form method="GET" action="{{ route('admin.refund-inventory') }}">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Search by product name or order ID" value="{{ request('search') }}">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Order ID</th>
                                <th>Refund Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($refunds as $refund)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $refund->product->image ? asset($refund->product->image) : asset('images/default.png') }}"
                                                class="img-thumbnail me-2" width="50" height="50">
                                            <span>{{ $refund->product->name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $refund->quantity }}</td>
                                    <td>#{{ $refund->order_id }}</td>
                                    <td>{{ $refund->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        @if ($refund->status == 0)
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($refund->status == 1)
                                            <span class="badge badge-success">Approved</span>
                                        @else
                                            <span class="badge badge-danger">Rejected</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info btn-detail" data-id="{{ $refund->id }}"
                                            data-bs-toggle="modal" data-bs-target="#detailModal">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>

                                        @if ($refund->status == 0)
                                            <button class="btn btn-sm btn-success btn-approve"
                                                data-id="{{ $refund->id }}">
                                                <i class="fas fa-check"></i> Approve
                                            </button>

                                            <button class="btn btn-sm btn-danger btn-reject" data-id="{{ $refund->id }}">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $refunds->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Refund Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="detailImage" src="" class="img-fluid rounded" style="max-height: 200px;">
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th>Product Name</th>
                            <td id="detailName"></td>
                        </tr>
                        <tr>
                            <th>Quantity</th>
                            <td id="detailQuantity"></td>
                        </tr>
                        <tr>
                            <th>Order ID</th>
                            <td id="detailOrderId"></td>
                        </tr>
                        <tr>
                            <th>Refund Date</th>
                            <td id="detailRefundDate"></td>
                        </tr>
                        <tr>
                            <th>Reason</th>
                            <td id="detailRefundNote"></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Show detail modal
            $('.btn-detail').click(function() {
                var id = $(this).data('id');

                $.ajax({
                    url: "/admin/refund-inventory/" + id + "/detail",
                    type: 'GET',
                    success: function(response) {
                        $('#detailImage').attr('src', response.product.image ?
                            "{{ asset('') }}" + response.product.image :
                            "{{ asset('images/default-product.png') }}");
                        $('#detailName').text(response.product.name);
                        $('#detailQuantity').text(response.quantity);
                        $('#detailOrderId').text('#' + response.order_id);
                        $('#detailRefundDate').text(new Date(response.created_at)
                            .toLocaleString());

                        $('#detailRefundNote').text(response.order_refund.reason + "tesst");
                    }
                });
            });

            // Approve refund
            $('.btn-approve').click(function() {
                var id = $(this).data('id');
                var btn = $(this);

                if (confirm('Are you sure you want to approve this refund?')) {
                    $.ajax({
                        url: "/admin/refund-inventory/" + id + "/approve",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });

            // Reject refund
            $('.btn-reject').click(function() {
                var id = $(this).data('id');

                if (confirm('Are you sure you want to reject this refund?')) {
                    $.ajax({
                        url: "/admin/refund-inventory/" + id + "/reject",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function() {
                            location.reload();
                        }
                    });
                }
            });
        });
    </script>
@endsection
