@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Customer</h5>
                    
                </div>
                <div class="card-body">
                    <!-- Search Box -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form action="{{ route('admin.customer') }}" method="GET">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama/email..." 
                                           value="{{ request('search') }}">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Customers Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Tanggal Daftar</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.customer.edit', $customer->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-outline-danger delete-btn" 
                                                        data-id="{{ $customer->id }}" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data customer</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between">
                        <div class="text-muted">
                            Menampilkan {{ $customers->firstItem() }} sampai {{ $customers->lastItem() }} dari {{ $customers->total() }} entri
                        </div>
                        <div>
                            {{ $customers->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus customer ini?
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Delete button click handler
        $('.delete-btn').click(function() {
            var customerId = $(this).data('id');
            var url = "{{ route('admin.customer.destroy', ':id') }}";
            url = url.replace(':id', customerId);
            
            $('#deleteForm').attr('action', url);
            $('#deleteModal').modal('show');
        });

        // Auto-focus search input
        $('input[name="search"]').focus();
    });
</script>
@endsection

@push('styles')
<style>
    .table th {
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        padding: 0.25rem 0.5rem;
    }
    .pagination {
        margin-bottom: 0;
    }
</style>
@endpush