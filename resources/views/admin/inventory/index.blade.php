@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Inventory Management</h1>
        <a href="{{ route('admin.inventory.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Add New Item
        </a>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Total Items Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalItems }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Value Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Value</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($totalValue, 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Low Stock Items</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $lowStockCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recently Added Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Recently Added</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $recentlyAddedCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Inventory Items</h6>
            <div class="d-flex">
                <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Search..." 
                           id="searchInput" onkeyup="searchTable()">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Total Value</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventories as $inventory)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($inventory->product->image_url ?? false)
                                        <img src="{{ $inventory->product->image_url }}" class="img-profile rounded-circle mr-3" width="40" height="40">
                                    @else
                                        <div class="img-profile rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $inventory->product->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-muted">{{ $inventory->product->sku ?? 'No SKU' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="progress mb-2" style="height: 5px;">
                                    @php
                                        $percentage = min(100, ($inventory->quantity / ($inventory->product->max_stock ?? 100)) * 100);
                                        $color = match(true) {
                                            $inventory->quantity == 0 => 'bg-danger',
                                            $inventory->quantity < 5 => 'bg-danger',
                                            $inventory->quantity < 10 => 'bg-warning',
                                            default => 'bg-success',
                                        };
                                    @endphp
                                    <div class="progress-bar {{ $color }}" role="progressbar" style="width: {{ $percentage }}%" 
                                         aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="badge {{ $inventory->quantity == 0 ? 'badge-danger' : 
                                              ($inventory->quantity < 5 ? 'badge-danger' : 
                                              ($inventory->quantity < 10 ? 'badge-warning' : 'badge-success')) }}">
                                    {{ $inventory->quantity }} in stock
                                </span>
                            </td>
                            <td>${{ number_format($inventory->unit_cost, 2) }}</td>
                            <td class="font-weight-bold">${{ number_format($inventory->total_cost, 2) }}</td>
                            <td>
                                {{ $inventory->last_restocked_at ? $inventory->last_restocked_at->format('M d, Y') : 'Never' }}
                                <div class="text-xs text-muted">
                                    {{ $inventory->last_restocked_at ? $inventory->last_restocked_at->diffForHumans() : '' }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <a href="{{ route('inventory.edit', $inventory->id) }}" class="btn btn-sm btn-primary mr-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('inventory.destroy', $inventory->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No inventory items found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $inventories->firstItem() }} to {{ $inventories->lastItem() }} of {{ $inventories->total() }} entries
                </div>
                <div>
                    {{ $inventories->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Warning -->
    @if($lowStockItems->count() > 0)
    <div class="card shadow mb-4 border-left-warning">
        <div class="card-header py-3 d-flex align-items-center bg-warning">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-exclamation-triangle mr-2"></i>Low Stock Alert
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr class="bg-warning-50">
                            <th>Product</th>
                            <th>Current Stock</th>
                            <th>Recommended</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lowStockItems as $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($item->product->image_url ?? false)
                                        <img src="{{ $item->product->image_url }}" class="img-profile rounded-circle mr-3" width="40" height="40">
                                    @else
                                        <div class="img-profile rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="font-weight-bold">{{ $item->product->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-muted">{{ $item->product->sku ?? 'No SKU' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="font-weight-bold text-danger">{{ $item->quantity }}</td>
                            <td>10</td>
                            <td>
                                <a href="{{ route('inventory.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-plus mr-1"></i> Restock
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Search Functionality -->
<script>
function searchTable() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("dataTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        let found = false;
        const td = tr[i].getElementsByTagName("td");
        
        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }
        
        tr[i].style.display = found ? "" : "none";
    }
}
</script>
@endsection