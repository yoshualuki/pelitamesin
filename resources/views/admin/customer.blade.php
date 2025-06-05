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
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Cari nama/email..." value="{{ request('search') }}">
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
                                                    <button class="btn btn-sm btn-outline-primary edit-btn"
                                                        data-id="{{ $customer->id }}" data-name="{{ $customer->name }}"
                                                        data-email="{{ $customer->email }}" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
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
                                Menampilkan {{ $customers->firstItem() }} sampai {{ $customers->lastItem() }} dari
                                {{ $customers->total() }} entri
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

    <!-- Edit Customer Modal -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Customer</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editCustomerForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_name">Nama</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_email">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
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

            // Edit button click handler
            $('.edit-btn').click(function() {
                var customerId = $(this).data('id');
                var name = $(this).data('name');
                var email = $(this).data('email');

                var url = "{{ route('admin.customer.update', ':id') }}";
                url = url.replace(':id', customerId);

                $('#editCustomerForm').attr('action', url);
                $('#edit_name').val(name);
                $('#edit_email').val(email);

                $('#editCustomerModal').modal('show');
            });

            // Auto-focus search input
            $('input[name="search"]').focus();

            // Form validation for edit customer
            $('#editCustomerForm').validate({
                rules: {
                    name: 'required',
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        minlength: 8
                    },
                    password_confirmation: {
                        equalTo: '#edit_password'
                    }
                },
                messages: {
                    name: 'Nama harus diisi',
                    email: {
                        required: 'Email harus diisi',
                        email: 'Masukkan email yang valid'
                    },
                    password: {
                        minlength: 'Password minimal 8 karakter'
                    },
                    password_confirmation: {
                        equalTo: 'Konfirmasi password harus sama dengan password'
                    }
                }
            });

            // Form validation for create customer
            $('#createCustomerForm').validate({
                rules: {
                    name: 'required',
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: '#password'
                    }
                },
                messages: {
                    name: 'Nama harus diisi',
                    email: {
                        required: 'Email harus diisi',
                        email: 'Masukkan email yang valid'
                    },
                    password: {
                        required: 'Password harus diisi',
                        minlength: 'Password minimal 8 karakter'
                    },
                    password_confirmation: {
                        required: 'Konfirmasi password harus diisi',
                        equalTo: 'Konfirmasi password harus sama dengan password'
                    }
                }
            });
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

        .error {
            color: #dc3545;
            font-size: 0.875em;
        }
    </style>
@endpush
